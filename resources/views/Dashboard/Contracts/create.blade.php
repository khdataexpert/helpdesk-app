<x-app-layout>
    <div class="container mx-auto p-4 md:p-8">
        {{-- Alerts --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 border border-red-300 px-4 py-3 rounded-lg mb-6">
                {{ __('text.form_has_errors') }}
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-100 text-green-800 border border-green-300 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-2xl border border-gray-200">
            <h1 class="text-3xl font-extrabold mb-8 text-gray-800 border-b pb-4 border-gray-100">
                {{ __('text.create_contract') }}
            </h1>

            <form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Contract Number --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.contract_number') }}</label>
                    <input type="text" name="contract_number" value="{{ old('contract_number') }}" required
                           class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('contract_number') border-red-500 @enderror">
                    @error('contract_number') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Notes --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.notes') ?? 'Notes' }}</label>
                    <textarea name="notes" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                </div>

                {{-- Client --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.contract_client') }}</label>
                    <select name="client_id" class="w-full border rounded-lg px-4 py-3 @error('client_id') border-red-500 @enderror" required>
                        <option value="">{{ __('text.select_client') ?? '-- Select --' }}</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Project (optional) --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.ticket_project') }}</label>
                    <select name="project_id" class="w-full border rounded-lg px-4 py-3">
                        <option value="">{{ __('text.none') }}</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">{{ __('text.contract_start_date') }}</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full border rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">{{ __('text.contract_end_date') }}</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full border rounded-lg px-4 py-3">
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.contract_status') }}</label>
                    <select name="status" class="w-full border rounded-lg px-4 py-3">
                        <option value="pending" @selected(old('status') == 'pending')>{{ __('text.status_pending') }}</option>
                        <option value="active" @selected(old('status') == 'active')>{{ __('text.status_active') }}</option>
                        <option value="expired" @selected(old('status') == 'expired')>{{ __('text.status_expired') }}</option>
                        <option value="cancelled" @selected(old('status') == 'cancelled')>{{ __('text.status_cancelled') ?? 'Cancelled' }}</option>
                    </select>
                </div>

                {{-- Attachment --}}
                <div class="mb-8">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.contract_attachment') }}</label>
                    <input type="file" name="attachment" class="w-full border rounded-lg px-4 py-3 bg-gray-50">
                </div>

                {{-- Submit --}}
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
