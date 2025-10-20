<x-app-layout>
    <div class="container mx-auto p-6">
        {{-- العنوان + زر الرجوع --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-800">{{ __('text.ticket_details') }}</h2>
            <a href="{{ route('tickets.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-150">
                <i class="fas fa-arrow-left me-2"></i> {{ __('text.back_to_tickets') }}
            </a>
        </div>

        {{-- تفاصيل التذكرة --}}
        <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.ticket_title') }}</h3>
                <p class="text-gray-900 text-base">{{ $ticket->title }}</p>
            </div>

            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.ticket_description') }}</h3>
                <p class="text-gray-800 text-base">{{ $ticket->description ?? __('text.no_description') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.ticket_type') }}</h3>
                    <p class="text-gray-900">{{ ucfirst($ticket->type) }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.ticket_priority') }}</h3>
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                        @if($ticket->priority == 'low') bg-green-100 text-green-800
                        @elseif($ticket->priority == 'medium') bg-yellow-100 text-yellow-800
                        @elseif($ticket->priority == 'high') bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.ticket_status') }}</h3>
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                        @if($ticket->status == 'open') bg-blue-100 text-blue-800
                        @elseif($ticket->status == 'in_progress') bg-yellow-100 text-yellow-800
                        @elseif($ticket->status == 'closed') bg-gray-200 text-gray-700
                        @endif">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.ticket_project') }}</h3>
                    <p class="text-gray-900">{{ $ticket->project->name ?? '-' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.ticket_team') }}</h3>
                    <p class="text-gray-900">{{ $ticket->team->name ?? '-' }}</p>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.ticket_assigned_to') }}</h3>
                    <p class="text-gray-900">{{ $ticket->assignedUser->name ?? __('Not Assigned') }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('text.ticket_created_by') }}</h3>
                <p class="text-gray-900">{{ $ticket->creator->name ?? '-' }}</p>
            </div>

            {{-- زرار Assign to Me لو المستخدم Agent --}}
            @if (auth()->user()->hasRole('Agent') && $ticket->assigned_to == null)
                <form action="{{ route('tickets.assignToMe', $ticket->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2 px-4 rounded-lg transition duration-150 shadow-md">
                        <i class="fas fa-user-plus me-1"></i> {{ __('Assign to Me') }}
                    </button>
                </form>
            @elseif(auth()->user()->id == $ticket->assigned_to)
                <div class="mt-4 text-green-700 font-semibold">
                    <i class="fas fa-check-circle me-1"></i> {{ __('Assigned to You') }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
