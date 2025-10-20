<x-app-layout>
    <div class="container mx-auto p-6 md:p-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-2xl border border-gray-200">
            <h1 class="text-3xl font-extrabold mb-8 text-gray-800 border-b pb-4 border-gray-100">
                {{ __('text.create_invoice') }}
            </h1>

            <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- رقم الفاتورة --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_number') }}</label>
                    <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" required
                           class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- العميل --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_client') }}</label>
                    <select name="client_id" class="w-full border rounded-lg px-4 py-3" required>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- المشروع --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_project') }}</label>
                    <select name="project_id" class="w-full border rounded-lg px-4 py-3">
                        <option value="">{{ __('text.none') }}</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- التاريخ --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_date') }}</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date') }}"
                           class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- الإجمالي --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_total') }}</label>
                    <input type="number" name="total" value="{{ old('total') }}" step="0.01"
                           class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- الحالة --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_status') }}</label>
                    <select name="status" class="w-full border rounded-lg px-4 py-3">
                        <option value="paid">{{ __('text.status_paid') }}</option>
                        <option value="pending">{{ __('text.status_pending') }}</option>
                        <option value="unpaid">{{ __('text.status_unpaid') }}</option>
                    </select>
                </div>

                {{-- مرفق --}}
                <div class="mb-8">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.invoice_attachment') }}</label>
                    <input type="file" name="attachment" class="w-full border rounded-lg px-4 py-3 bg-gray-50">
                </div>

                {{-- زر الحفظ --}}
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-blue-700 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition">
                        {{ __('text.save_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
