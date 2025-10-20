<x-app-layout>
    <div class="max-w-6xl mx-auto p-6 space-y-8">

        {{-- ✅ Header --}}
        <div class="flex items-center justify-between">
            <h2 class="text-4xl font-extrabold text-gray-800 flex items-center gap-3">
                <i class="fas fa-file-contract text-blue-600"></i>
                {{ __('text.contract_details') }}
            </h2>
            <a href="{{ route('contracts.index') }}" 
               class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-xl font-semibold shadow-sm transition-all flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> {{ __('text.back') }}
            </a>
        </div>

        {{-- ✅ Contract Summary Card --}}
        <div class="bg-gradient-to-r from-blue-50 to-white shadow-lg rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Left side --}}
                <div class="space-y-3">
                    <h3 class="text-xl font-semibold text-gray-700 border-b pb-2">{{ __('text.contract_information') }}</h3>
                    <div class="space-y-2 text-gray-700">
                        <p><strong>{{ __('text.contract_number') }}:</strong> #{{ $contract->contract_number }}</p>
                        <p><strong>{{ __('text.contract_start_date') }}:</strong> {{ optional($contract->start_date)->format('Y-m-d') }}</p>
                        <p><strong>{{ __('text.contract_end_date') }}:</strong> {{ optional($contract->end_date)->format('Y-m-d') }}</p>
                        <p>
                            <strong>{{ __('text.contract_status') }}:</strong>
                            @switch($contract->status)
                                @case('active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i> {{ __('text.status_active') }}
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
                                        <i class="fas fa-hourglass-half mr-1"></i> {{ __('text.status_pending') }}
                                    </span>
                                    @break
                                @case('expired')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-semibold">
                                        <i class="fas fa-times-circle mr-1"></i> {{ __('text.status_expired') }}
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm font-semibold">
                                        <i class="fas fa-question-circle mr-1"></i> {{ __('text.unknown') }}
                                    </span>
                            @endswitch
                        </p>
                    </div>
                </div>

                {{-- Right side --}}
                <div class="space-y-3">
                    <h3 class="text-xl font-semibold text-gray-700 border-b pb-2">{{ __('text.client_project') }}</h3>
                    <div class="space-y-2 text-gray-700">
                        <p><strong>{{ __('text.contract_client') }}:</strong> {{ $contract->client->name ?? __('text.none') }}</p>
                        <p><strong>{{ __('text.contract_project') }}:</strong> {{ $contract->project->name ?? __('text.none') }}</p>
                        <p><strong>{{ __('text.contract_creator') }}:</strong> {{ $contract->creator->name ?? __('text.none') }}</p>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-100"></div>

            {{-- ✅ Footer info --}}
            <div class="p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="text-gray-700 max-w-2xl">
                    <h4 class="font-semibold text-gray-800 mb-1">{{ __('text.contract_notes') }}</h4>
                    <p class="whitespace-pre-line">{{ $contract->notes ?? __('text.none') }}</p>
                </div>

                <div class="flex items-center gap-2">
                    @if ($contract->attachment)
                        <a href="{{ asset('storage/' . $contract->attachment) }}" target="_blank"
                           class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl shadow-md transition-all">
                            <i class="fas fa-paperclip"></i> {{ __('text.view_attachment') }}
                        </a>
                    @else
                        <span class="text-gray-400">{{ __('text.no_attachment') }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ✅ Action Buttons --}}
        <div class="flex justify-end gap-3">
            @can('edit contracts')
                <a href="{{ route('contracts.edit', $contract->id) }}" 
                   class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-5 py-2.5 rounded-xl shadow-md transition-all font-semibold">
                    <i class="fas fa-edit"></i> {{ __('text.edit_btn') }}
                </a>
            @endcan

            @can('delete contracts')
                <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST"
                      onsubmit="return confirm('{{ __('text.confirm_delete') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl shadow-md transition-all font-semibold">
                        <i class="fas fa-trash-alt"></i> {{ __('text.delete_btn') }}
                    </button>
                </form>
            @endcan
        </div>

    </div>
</x-app-layout>
