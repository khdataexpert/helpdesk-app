@php
$user = auth()->user();
$isRtl = app()->isLocale('ar');
$direction = $isRtl ? 'rtl' : 'ltr';
$marginClass = $isRtl ? 'ms-3' : 'me-3';
$asideZIndex = 'z-50';
@endphp

<div dir="{{ $direction }}"
    class="fixed inset-y-0 w-64 text-white p-4 shadow-xl transition-transform duration-300 transform 
             {{ $isRtl ? 'right-0' : 'left-0' }} {{ $asideZIndex }}"
    style="background-color: #235784;">

    {{-- رأس القائمة --}}
    <div class="flex items-center border-b pb-4 mb-6" style="border-color: #40A8C4;">
        <h3 class="text-xl font-bold">{{ __('text.app_name') }}</h3>
    </div>

    {{-- روابط القائمة --}}
    <nav class="space-y-2">

        {{-- Dashboard --}}
        @php $isActive = request()->routeIs('dashboard'); @endphp
        <a href="{{ route('dashboard') }}"
            class="flex items-center p-3 rounded-lg transition duration-150 text-white hover:bg-opacity-80 space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}"
            style="background-color: {{ $isActive ? '#40A8C4' : 'transparent' }};">
            <i class="fas fa-home {{ $marginClass }} w-5 h-5"></i>
            <span class="font-medium">{{ __('text.dashboard_title') }}</span>
        </a>

        {{-- ✅ Users --}}
        @if($user && $user->hasAnyPermission(['view users', 'add users']))
            @php $isActive = request()->routeIs('users.*'); @endphp
            <a href="{{ route('users.index') }}"
                class="flex items-center p-3 space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} rounded-lg transition duration-150 text-white hover:bg-opacity-80"
                style="background-color: {{ $isActive ? '#40A8C4' : 'transparent' }};">
                <i class="fas fa-users {{ $marginClass }} w-5 h-5"></i>
                <span class="font-medium">{{ __('text.users_link') }}</span>
            </a>
        @endif

        {{-- ✅ Teams --}}
        @if ($user && $user->hasAnyPermission(['view teams', 'add teams', 'edit teams', 'delete teams']))
            @php $isActive = request()->routeIs('teams.*'); @endphp
            <a href="{{ route('teams.index') }}"
                class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} p-3 rounded-lg transition duration-150 text-white hover:bg-opacity-80"
                style="background-color: {{ $isActive ? '#40A8C4' : 'transparent' }};">
                <i class="fas fa-sitemap {{ $marginClass }} w-5 h-5"></i>
                <span class="font-medium">{{ __('text.teams_management') }}</span>
            </a>
        @endif

        {{-- ✅ Projects --}}
        @if ($user && $user->hasAnyPermission(['view projects','view own projects', 'add projects', 'edit projects', 'delete projects']))
            @php $isActive = request()->routeIs('projects.*'); @endphp
            <a href="{{ route('projects.index') }}"
                class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} p-3 rounded-lg transition duration-150 text-white hover:bg-opacity-80"
                style="background-color: {{ $isActive ? '#40A8C4' : 'transparent' }};">
                <i class="fas fa-folder-open {{ $marginClass }} w-5 h-5"></i>
                <span class="font-medium">{{ __('text.projects_management') }}</span>
            </a>
        @endif

        {{-- ✅ Tickets --}}
        @if ($user && $user->hasAnyPermission(['view tickets', 'add tickets', 'edit tickets', 'delete tickets', 'view own tickets']))
            @php $isActive = request()->routeIs('tickets.*'); @endphp
            <a href="{{ route('tickets.index') }}"
                class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} p-3 rounded-lg transition duration-150 text-white hover:bg-opacity-80"
                style="background-color: {{ $isActive ? '#40A8C4' : 'transparent' }};">
                <i class="fas fa-ticket-alt {{ $marginClass }} w-5 h-5"></i>
                <span class="font-medium">{{ __('text.tickets_management') }}</span>
            </a>
        @endif

        {{-- ✅ Contracts --}}
        @if ($user && $user->hasAnyPermission(['view contracts', 'add contracts', 'edit contracts', 'delete contracts','view own contracts']))
            @php $isActive = request()->routeIs('contracts.*'); @endphp
            <a href="{{ route('contracts.index') }}"
                class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} p-3 rounded-lg transition duration-150 text-white hover:bg-opacity-80"
                style="background-color: {{ $isActive ? '#40A8C4' : 'transparent' }};">
                <i class="fas fa-file-contract {{ $marginClass }} w-5 h-5"></i>
                <span class="font-medium">{{ __('text.contracts_management') }}</span>
            </a>
        @endif

        {{-- ✅ Invoices --}}
        @if ($user && $user->hasAnyPermission(['view invoices', 'add invoices', 'edit invoices', 'delete invoices','view own invoices']))
            @php $isActive = request()->routeIs('invoices.*'); @endphp
            <a href="{{ route('invoices.index') }}"
                class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} p-3 rounded-lg transition duration-150 text-white hover:bg-opacity-80"
                style="background-color: {{ $isActive ? '#40A8C4' : 'transparent' }};">
                <i class="fas fa-file-invoice-dollar {{ $marginClass }} w-5 h-5"></i>
                <span class="font-medium">{{ __('text.invoices_management') }}</span>
            </a>
        @endif

        {{-- ✅ Roles --}}
        @if ($user && $user->hasAnyPermission(['view Roles', 'add Roles', 'edit Roles', 'delete Roles']))
            @php $isActive = request()->routeIs('roles.*'); @endphp
            <a href="{{ route('roles.index') }}"
                class="flex items-center space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }} p-3 rounded-lg transition duration-150 text-white hover:bg-opacity-80"
                style="background-color: {{ $isActive ? '#40A8C4' : 'transparent' }};">
                <i class="fas fa-user-tag {{ $marginClass }} w-5 h-5"></i>
                <span class="font-medium">{{ __('text.roles_management') }}</span>
            </a>
        @endif

    </nav>
</div>
