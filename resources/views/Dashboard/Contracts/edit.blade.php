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
                {{ __('text.edit_contract') }}
            </h1>

            <form action="{{ route('contracts.update', $contract->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Contract Number --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.contract_number') }}</label>
                    <input type="text" name="contract_number" value="{{ old('contract_number', $contract->contract_number) }}" required
                           class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Notes --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.notes') ?? 'Notes' }}</label>
                    <textarea name="notes" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">{{ old('notes', $contract->notes) }}</textarea>
                </div>

                {{-- Client --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.contract_client') }}</label>
                    <select name="client_id" class="w-full border rounded-lg px-4 py-3">
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id', $contract->client_id) == $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Project --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.ticket_project') }}</label>
                    <select name="project_id" class="w-full border rounded-lg px-4 py-3">
                        <option value="">{{ __('text.none') }}</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id', $contract->project_id) == $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">{{ __('text.contract_start_date') }}</label>
                        <input type="date" name="start_date" value="{{ old('start_date', optional($contract->start_date)->toDateString()) }}" class="w-full border rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">{{ __('text.contract_end_date') }}</label>
                        <input type="date" name="end_date" value="{{ old('end_date', optional($contract->end_date)->toDateString()) }}" class="w-full border rounded-lg px-4 py-3">
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.contract_status') }}</label>
                    <select name="status" class="w-full border rounded-lg px-4 py-3">
                        <option value="pending" @selected(old('status', $contract->status) == 'pending')>{{ __('text.status_pending') }}</option>
                        <option value="active" @selected(old('status', $contract->status) == 'active')>{{ __('text.status_active') }}</option>
                        <option value="expired" @selected(old('status', $contract->status) == 'expired')>{{ __('text.status_expired') }}</option>
                        <option value="cancelled" @selected(old('status', $contract->status) == 'cancelled')>{{ __('text.status_cancelled') ?? 'Cancelled' }}</option>
                    </select>
                </div>

                {{-- Attachment --}}
                <div class="mb-8">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.contract_attachment') }}</label>
                    <input type="file" name="attachment" class="w-full border rounded-lg px-4 py-3 bg-gray-50">
                    @if ($contract->attachment)
                        <p class="text-sm mt-2">
                            {{ __('text.current_file') }}:
                            <a href="{{ asset('storage/' . $contract->attachment) }}" class="text-blue-600 underline" target="_blank">
                                {{ __('text.view_attachment') }}
                            </a>
                        </p>
                    @endif
                </div>

                {{-- Submit --}}
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
