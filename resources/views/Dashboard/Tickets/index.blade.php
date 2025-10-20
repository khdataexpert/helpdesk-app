<x-app-layout>
    <div class="container mx-auto p-4 md:p-6">

        {{-- العنوان + زر إنشاء تذكرة --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-800">{{ __('text.tickets_management') }}</h2>

            @can('add tickets')
                <a href="{{ route('tickets.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-200 flex items-center">
                    <i class="fas fa-plus me-2"></i> {{ __('text.create_ticket') }}
                </a>
            @endcan
        </div>

        {{-- رسالة النجاح --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 shadow-sm text-sm">
                <p class="font-semibold">{{ __('text.success_heading') }}</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- جدول التذاكر --}}
        @if (auth()->user()->can('view tickets') || auth()->user()->can('view own tickets'))
            <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
                <table class="min-w-full table-fixed text-sm text-gray-700 leading-normal">
                    <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold tracking-wider">
                        <tr>
                            <th class="px-3 py-2 w-10 text-left">#</th>
                            <th class="px-3 py-2 w-40 text-left">{{ __('text.ticket_title') }}</th>
                            <th class="px-3 py-2 w-20 text-left hidden md:table-cell">{{ __('text.ticket_type') }}</th>
                            <th class="px-3 py-2 w-24 text-left">{{ __('text.ticket_status') }}</th>
                            <th class="px-3 py-2 w-24 text-left hidden lg:table-cell">{{ __('text.ticket_priority') }}</th>
                            <th class="px-3 py-2 w-32 text-left hidden lg:table-cell">{{ __('text.ticket_assigned_to') }}</th>
                            <th class="px-3 py-2 w-32 text-left hidden xl:table-cell">{{ __('text.ticket_project') }}</th>
                            <th class="px-3 py-2 w-28 text-center">{{ __('text.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-3 py-2 font-semibold text-gray-800">{{ $ticket->id }}</td>
                                <td class="px-3 py-2 truncate">{{ $ticket->title }}</td>
                                <td class="px-3 py-2 hidden md:table-cell">{{ ucfirst($ticket->type) }}</td>
                                <td class="px-3 py-2">
                                    @php
                                        $statusColors = [
                                            'open' => 'bg-green-100 text-green-800',
                                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                                            'resolved' => 'bg-blue-100 text-blue-800',
                                            'closed' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 rounded-lg text-xs font-semibold {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 hidden lg:table-cell">{{ ucfirst($ticket->priority) }}</td>
                                <td class="px-3 py-2 hidden lg:table-cell">{{ $ticket->assignedUser->name ?? '-' }}</td>
                                <td class="px-3 py-2 hidden xl:table-cell">{{ $ticket->project?->name ?? '-' }}</td>

                                <td class="px-3 py-2 text-center flex justify-center gap-2 flex-wrap">

                                    <a href="{{ route('tickets.show', $ticket->id) }}"
                                        class="text-green-600 hover:text-green-800 font-medium text-xs">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @can('edit tickets')
                                        <a href="{{ route('tickets.edit', $ticket->id) }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium text-xs">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @if (auth()->user()->hasRole('Agent') && $ticket->assigned_to === null && !auth()->user()->can('edit tickets'))
                                        <form action="{{ route('tickets.assignToMe', $ticket->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="bg-blue-500 hover:bg-blue-600 text-white text-[10px] font-semibold py-1 px-2 rounded-md transition duration-150 shadow">
                                                <i class="fas fa-user-plus"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @can('delete tickets')
                                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST"
                                            onsubmit="return confirm('{{ __('text.confirm_delete') }}')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 font-medium text-xs">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6 text-gray-500">
                                    {{ __('text.no_tickets_yet') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
