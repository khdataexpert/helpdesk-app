<x-app-layout>
    <div class="container mx-auto p-4 md:p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-800">{{ __('text.invoices_management') }}</h2>

            @if (auth()->user()->can('add invoices'))
                <a href="{{ route('invoices.create') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 shadow-md flex items-center">
                    <i class="fas fa-plus me-2"></i> {{ __('text.create_invoice') }}
                </a>
            @endif
        </div>

        {{-- ✅ Flash Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
        @endif
@if(auth()->user()->can('view invoices')||auth()->user()->can('view own invoices'))

        {{-- ✅ Table --}}
        <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">#</th>
                        <th class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">{{ __('text.invoice_number') }}</th>
                        <th class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">{{ __('text.invoice_date') }}</th>
                        <th class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">{{ __('text.invoice_client') }}</th>
                        <th class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">{{ __('text.invoice_project') }}</th>
                        <th class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">{{ __('text.invoice_total') }}</th>
                        <th class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">{{ __('text.invoice_status') }}</th>
                        <th class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">{{ __('text.invoice_attachment') }}</th>
                        <th class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">{{ __('text.invoice_creator') }}</th>
                        <th class="px-5 py-3 border-b-2 bg-blue-100"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4 border-b">{{ $loop->iteration }}</td>
                            <td class="px-5 py-4 border-b font-semibold">{{ $invoice->invoice_number }}</td>
                            <td class="px-5 py-4 border-b">{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                            <td class="px-5 py-4 border-b">{{ $invoice->client->name ?? __('text.none') }}</td>
                            <td class="px-5 py-4 border-b">{{ $invoice->project->name ?? __('text.none') }}</td>
                            <td class="px-1 py-4 border-b font-bold text-gray-800">{{ number_format($invoice->total, 2) }} EG</td>
                            <td class="px-5 py-4 border-b">
                                @switch($invoice->status)
                                    @case('paid')
                                        <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800 font-semibold">{{ __('text.status_paid') }}</span>
                                        @break
                                    @case('pending')
                                        <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800 font-semibold">{{ __('text.status_pending') }}</span>
                                        @break
                                    @default
                                        <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-800 font-semibold">{{ __('text.status_unpaid') }}</span>
                                @endswitch
                            </td>
                            <td class="px-7 py-4 border-b">
                                @if ($invoice->attachment)
                                    <a href="{{ asset('storage/' . $invoice->attachment) }}" target="_blank" class="text-blue-600 underline"> <i class="fas fa-eye"></i></a>
                                @else
                                    <span class="text-gray-400">{{ __('text.none') }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 border-b">{{ $invoice->creator->name ?? __('text.none') }}</td>
                            <td class="px-5 py-4 border-b text-right">
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="text-green-500 hover:text-green-700 me-3 font-medium">
                                    <i class="fas fa-eye"></i> {{ __('text.view') }}
                                </a>
                                @can('edit invoices')
                                    
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="text-blue-500 hover:text-blue-700 me-3 font-medium">
                                    <i class="fas fa-edit"></i> {{ __('text.edit_btn') }}
                                </a>
                                @endcan
                                @can('delete invoices')
                                    
                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('text.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">
                                        <i class="fas fa-trash-alt"></i> {{ __('text.delete_btn') }}
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            <div class="p-4 bg-white border-t">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
