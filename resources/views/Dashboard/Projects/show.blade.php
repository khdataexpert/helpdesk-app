<x-app-layout>
    <div class="container mx-auto p-6">

        {{-- العنوان --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-800">
                {{ __('text.project_details') }}
            </h2>

            <a href="{{ route('projects.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-150">
                <i class="fas fa-arrow-left me-2"></i> {{ __('text.back_to_projects') }}
            </a>
        </div>

        {{-- تفاصيل المشروع --}}
        <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.project_name') }}</h3>
                <p class="text-gray-900 text-base">{{ $project->name }}</p>
            </div>

            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.project_description') }}</h3>
                <p class="text-gray-800 text-base">{{ $project->description ?? __('text.no_description') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.project_client') }}</h3>
                    <p class="text-gray-900">{{ $project->client->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.project_team') }}</h3>
                    <p class="text-gray-900">{{ $project->team->name ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.project_status') }}</h3>
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                        @if($project->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($project->status == 'in_progress') bg-blue-100 text-blue-800
                        @elseif($project->status == 'completed') bg-green-100 text-green-800
                        @endif">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.Assigned_To') }}</h3>
                    <p class="text-gray-900">{{ $project->assignedUser->name ?? __('Not Assigned') }}</p>
                </div>
            </div>

            {{-- زرار Assign to Me لو Agent --}}
            @if (auth()->user()->hasRole('Agent') && $project->assigned_to == null)
                <form action="{{ route('projects.assignToMe', $project->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold py-2 px-4 rounded-lg transition duration-150 shadow-md">
                        <i class="fas fa-user-plus me-1"></i> {{ __('Assign to Me') }}
                    </button>
                </form>
            @elseif(auth()->user()->id == $project->assigned_to)
                <div class="mt-3 text-green-700 font-semibold">
                    <i class="fas fa-check-circle me-1"></i> {{ __('Assigned to You') }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
