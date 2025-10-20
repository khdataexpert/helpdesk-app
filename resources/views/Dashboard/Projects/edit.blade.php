<x-app-layout>
    <div class="container mx-auto p-4 md:p-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-2xl border border-gray-200">

            {{-- عنوان الصفحة --}}
            <h1 class="text-3xl font-extrabold mb-8 text-gray-800 border-b pb-4 border-gray-100">
                {{ __('text.edit_project') }}
            </h1>

            {{-- Alerts --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative"
                    role="alert">
                    <strong class="font-bold">{{ __('text.form_has_errors') }}</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative"
                    role="alert">
                    <strong class="font-bold">{{ session('success') }}</strong>
                </div>
            @endif

            <form method="POST" action="{{ route('projects.update', $project->id) }}">
                @csrf
                @method('PUT')

                {{-- اسم المشروع --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.project_name') }}</label>
                    <input type="text" name="name" value="{{ old('name', $project->name) }}" required
                        class="shadow border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150 @error('name') border-red-500 @enderror"
                        placeholder="{{ __('text.enter_project_name') }}">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- وصف المشروع --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.project_description') }}</label>
                    <textarea name="description"
                        class="shadow border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150"
                        placeholder="{{ __('text.enter_project_description') }}">{{ old('description', $project->description) }}</textarea>
                </div>

                {{-- العميل --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.project_client') }}</label>
                    <select name="client_id"
                        class="shadow border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150">
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id', $project->client_id) == $client->id)>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- الفريق --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.project_team') }}</label>
                    <select name="team_id"
                        class="shadow border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150">
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" @selected(old('team_id', $project->team_id) == $team->id)>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @can('assign projects')
                <div class="mb-6">
                    <label for="assigned_to"
                        class="block text-gray-700 font-semibold mb-2">{{ __('text.assigned_to') }}</label>
                    <select name="assigned_to" id="assigned_to"
                        class="shadow border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150">
                        <option value="">{{ __('text.select_agent') }}</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}" @selected(old('assigned_to', $project->assigned_to) == $agent->id)>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endcan

                <div class="mb-8">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.project_status') }}</label>
                    <select name="status"
                        class="shadow border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150">
                        <option value="active" @selected(old('status', $project->status) == 'active')>{{ __('text.status_active') }}</option>
                        <option value="pending" @selected(old('status', $project->status) == 'pending')>{{ __('text.status_pending') }}</option>
                        <option value="completed" @selected(old('status', $project->status) == 'completed')>{{ __('text.status_completed') }}
                        </option>
                    </select>
                </div>

                {{-- زر التحديث --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-700 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg focus:outline-none focus:shadow-outline">
                        <i class="fas fa-save me-2"></i> {{ __('text.update_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
