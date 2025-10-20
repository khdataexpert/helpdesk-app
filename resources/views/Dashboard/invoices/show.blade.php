<x-app-layout>
    <div class="max-w-6xl mx-auto p-6 space-y-8">

        {{-- ✅ Header --}}
        <div class="flex items-center justify-between">
            <h2 class="text-4xl font-extrabold text-gray-800 flex items-center gap-3">
                <i class="fas fa-file-invoice text-blue-600"></i>
                {{ __('text.invoice_details') }}
            </h2>
            <a href="{{ route('invoices.index') }}" 
               class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-xl font-semibold shadow-sm transition-all flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> {{ __('text.back') }}
            </a>
        </div>

        {{-- ✅ Invoice Summary Card --}}
        <div class="bg-gradient-to-r from-blue-50 to-white shadow-lg rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Left side --}}
                <div class="space-y-3">
                    <h3 class="text-xl font-semibold text-gray-700 border-b pb-2">{{ __('text.invoice_information') }}</h3>
                    <div class="space-y-2 text-gray-700">
                        <p><strong>{{ __('text.invoice_number') }}:</strong> #{{ $invoice->invoice_number }}</p>
                        <p><strong>{{ __('text.invoice_date') }}:</strong> {{ $invoice->invoice_date->format('Y-m-d') }}</p>
                        <p>
                            <strong>{{ __('text.invoice_status') }}:</strong>
                            @switch($invoice->status)
                                @case('paid')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i> {{ __('text.status_paid') }}
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
                                        <i class="fas fa-hourglass-half mr-1"></i> {{ __('text.status_pending') }}
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-semibold">
                                        <i class="fas fa-times-circle mr-1"></i> {{ __('text.status_unpaid') }}
                                    </span>
                            @endswitch
                        </p>
                    </div>
                </div>

                {{-- Right side --}}
                <div class="space-y-3">
                    <h3 class="text-xl font-semibold text-gray-700 border-b pb-2">{{ __('text.client_project') }}</h3>
                    <div class="space-y-2 text-gray-700">
                        <p><strong>{{ __('text.invoice_client') }}:</strong> {{ $invoice->client->name ?? __('text.none') }}</p>
                        <p><strong>{{ __('text.invoice_project') }}:</strong> {{ $invoice->project->name ?? __('text.none') }}</p>
                        <p><strong>{{ __('text.invoice_creator') }}:</strong> {{ $invoice->creator->name ?? __('text.none') }}</p>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-100"></div>

            {{-- ✅ Footer info --}}
            <div class="p-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-gray-700">
                    <strong>{{ __('text.invoice_total') }}:</strong>
                    <span class="text-3xl font-extrabold text-gray-900">
                        {{ number_format($invoice->total, 2) }} EG
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    @if ($invoice->attachment)
                        <a href="{{ asset('storage/' . $invoice->attachment) }}" target="_blank"
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
            @can('edit invoices')
                <a href="{{ route('invoices.edit', $invoice->id) }}" 
                   class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-5 py-2.5 rounded-xl shadow-md transition-all font-semibold">
                    <i class="fas fa-edit"></i> {{ __('text.edit_btn') }}
                </a>
            @endcan

            @can('delete invoices')
                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
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
