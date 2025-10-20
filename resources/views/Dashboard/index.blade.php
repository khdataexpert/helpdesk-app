<x-app-layout>
    @php
        $isRtl = app()->isLocale('ar');
        $direction = $isRtl ? 'rtl' : 'ltr';
        $textAlign = $isRtl ? 'text-right' : 'text-left';
    @endphp

    <div class="max-w-7xl mx-auto p-8 space-y-8" dir="{{ $direction }}">
        {{-- ✅ Header --}}
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-gray-800 mb-3">
                {{ $isRtl ? 'لوحة التحكم' : 'Dashboard' }}
            </h1>
            <p class="text-gray-600">
                {{ $isRtl ? 'مرحباً بك، ' : 'Welcome, ' }} <strong>{{ $user->name }}</strong>
            </p>
        </div>

        {{-- ✅ Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @can('view users')
                <x-dashboard-card icon="fas fa-users" color="blue" 
                    :title="$isRtl ? 'المستخدمين' : 'Users'"
                    :count="$stats['users'] ?? 0"
                    :link="route('users.index')" />
            @endcan

            @canany(['view projects', 'view own projects'])
                <x-dashboard-card icon="fas fa-diagram-project" color="cyan"
                    :title="$isRtl ? 'المشروعات' : 'Projects'"
                    :count="$stats['projects'] ?? 0"
                    :link="route('projects.index')" />
            @endcanany

            @canany(['view tickets', 'view own tickets'])
                <x-dashboard-card icon="fas fa-ticket-alt" color="green"
                    :title="$isRtl ? 'التذاكر' : 'Tickets'"
                    :count="$stats['tickets'] ?? 0"
                    :link="route('tickets.index')" />
            @endcanany

            @canany(['view contracts', 'view own contracts'])
                <x-dashboard-card icon="fas fa-file-contract" color="purple"
                    :title="$isRtl ? 'العقود' : 'Contracts'"
                    :count="$stats['contracts'] ?? 0"
                    :link="route('contracts.index')" />
            @endcanany

            @canany(['view invoices', 'view own invoices'])
                <x-dashboard-card icon="fas fa-file-invoice-dollar" color="orange"
                    :title="$isRtl ? 'الفواتير' : 'Invoices'"
                    :count="$stats['invoices'] ?? 0"
                    :link="route('invoices.index')" />
            @endcanany

            @can('view teams')
                <x-dashboard-card icon="fas fa-people-group" color="indigo"
                    :title="$isRtl ? 'الفرق' : 'Teams'"
                    :count="$stats['teams'] ?? 0"
                    :link="route('teams.index')" />
            @endcan
        </div>

        {{-- ✅ Charts Section --}}
        <div class="bg-white shadow-lg rounded-2xl p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                {{ $isRtl ? 'التحليلات والإحصائيات' : 'Analytics & Insights' }}
            </h2>
            <canvas id="dashboardChart" class="w-full h-64"></canvas>
        </div>
    </div>

    {{-- ✅ Chart.js for Analytics --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('dashboardChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($stats)) !!},
                datasets: [{
                    label: '{{ $isRtl ? "الإحصائيات" : "Statistics" }}',
                    data: {!! json_encode(array_values($stats)) !!},
                    backgroundColor: [
                        '#3b82f6', '#06b6d4', '#10b981', '#8b5cf6', '#f59e0b', '#6366f1'
                    ],
                    borderRadius: 12,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</x-app-layout>
