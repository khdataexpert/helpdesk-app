<x-app-layout>
    @php
        $isRtl = app()->isLocale('ar');
        $textDirection = $isRtl ? 'text-right' : 'text-left';
    @endphp

    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-semibold text-gray-800 {{ $textDirection }}">
                {{ __('text.team_details') }}
            </h2>

            <a href="{{ route('teams.index') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200">
               <i class="fas fa-arrow-left"></i> {{ __('text.back_to_teams') }}
            </a>
        </div>

        {{-- ✅ بطاقة تفاصيل الفريق --}}
        <div class="bg-white shadow-xl rounded-xl p-6 border border-gray-100 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('text.team_name') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $team->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('text.team_specialization') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $team->Specialization ?? __('text.not_available') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('text.team_lead') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $team->lead?->name ?? __('text.not_assigned') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('text.members_count') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $team->users->count() }}</p>
                </div>
            </div>
        </div>

        {{-- ✅ عرض الأعضاء --}}
        <div class="bg-white shadow-xl rounded-xl p-6 border border-gray-100 mb-6">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('text.team_members') }}</h3>

            @if($team->users->isEmpty())
                <p class="text-gray-600">{{ __('text.no_members_in_team') }}</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($team->users as $user)
                        <li class="py-3 flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                            @can('view users')
                                <a href="{{ route('users.show', $user->id) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium transition">
                                   <i class="fas fa-eye"></i> {{ __('text.view_user') }}
                                </a>
                            @endcan
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- ✅ عرض المشاريع --}}
        <div class="bg-white shadow-xl rounded-xl p-6 border border-gray-100">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('text.team_projects') }}</h3>

            @if($team->projects->isEmpty())
                <p class="text-gray-600">{{ __('text.no_projects_assigned') }}</p>
            @else
                <ul class="list-disc ps-6">
                    @foreach($team->projects as $project)
                        <li class="mb-2 text-gray-800">
                            {{ $project->name ?? __('text.project_no_name') }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
