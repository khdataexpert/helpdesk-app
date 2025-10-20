<x-app-layout>
    <div class="container mx-auto p-6 md:p-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-2xl border border-gray-200">
            <h1 class="text-3xl font-extrabold mb-8 text-gray-800 border-b pb-4 border-gray-100">
                {{ __('text.edit_invoice') }}
            </h1>

            <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- رقم الفاتورة --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_number') }}</label>
                    <input type="text" name="invoice_number" value="{{ $invoice->invoice_number }}"
                           class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- العميل --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_client') }}</label>
                    <select name="client_id" class="w-full border rounded-lg px-4 py-3">
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" @selected($client->id == $invoice->client_id)>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- المشروع --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_project') }}</label>
                    <select name="project_id" class="w-full border rounded-lg px-4 py-3">
                        <option value="">{{ __('text.none') }}</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected($project->id == $invoice->project_id)>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- التاريخ والإجمالي --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_date') }}</label>
                        <input type="date" name="invoice_date" value="{{ $invoice->invoice_date->format('Y-m-d') }}"
                               class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_total') }}</label>
                        <input type="number" step="0.01" name="total" value="{{ $invoice->total }}"
                               class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- الحالة --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_status') }}</label>
                    <select name="status" class="w-full border rounded-lg px-4 py-3">
                        <option value="paid" @selected($invoice->status == 'paid')>{{ __('text.status_paid') }}</option>
                        <option value="pending" @selected($invoice->status == 'pending')>{{ __('text.status_pending') }}</option>
                        <option value="unpaid" @selected($invoice->status == 'unpaid')>{{ __('text.status_unpaid') }}</option>
                    </select>
                </div>

                {{-- مرفق --}}
                <div class="mb-8">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_attachment') }}</label>
                    <input type="file" name="attachment" class="w-full border rounded-lg px-4 py-3 bg-gray-50">
                    @if ($invoice->attachment)
                        <p class="text-sm mt-2">
                            {{ __('text.current_file') }}:
                            <a href="{{ asset('storage/' . $invoice->attachment) }}" class="text-blue-600 underline" target="_blank">
                                {{ __('text.view_attachment') }}
                            </a>
                        </p>
                    @endif
                </div>

                {{-- زر التحديث --}}
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-blue-700 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition">
                        {{ __('text.update_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
