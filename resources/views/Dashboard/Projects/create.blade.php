<x-app-layout>
    <div class="container mx-auto p-4 md:p-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-2xl border border-gray-200">

            {{-- العنوان --}}
            <h1 class="text-3xl font-extrabold mb-8 text-gray-800 border-b pb-4 border-gray-100">
                {{ __('text.create_project') }}
            </h1>

            {{-- رسائل التنبيه --}}
            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 border border-green-300">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 border border-red-300">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ __('text.form_has_errors') }}
                    <ul class="list-disc list-inside mt-2 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- الفورم --}}
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                {{-- اسم المشروع --}}
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">{{ __('text.project_name') }}</label>
                    <input type="text" name="name" id="name" required
                        class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150 @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}" placeholder="{{ __('text.enter_project_name') }}">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- وصف المشروع --}}
                <div class="mb-6">
                    <label for="description" class="block text-gray-700 font-semibold mb-2">{{ __('text.project_description') }}</label>
                    <textarea name="description" id="description" rows="4"
                        class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150 @error('description') border-red-500 @enderror"
                        placeholder="{{ __('text.enter_project_description') }}">{{ old('description') }}</textarea>
                </div>

                {{-- العميل --}}
                <div class="mb-6">
                    <label for="client_id" class="block text-gray-700 font-semibold mb-2">{{ __('text.project_client') }}</label>
                    <select name="client_id" id="client_id"
                        class="shadow border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150">
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- الفريق --}}
                <div class="mb-6">
                    <label for="team_id" class="block text-gray-700 font-semibold mb-2">{{ __('text.project_team') }}</label>
                    <select name="team_id" id="team_id"
                        class="shadow border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150">
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" @selected(old('team_id') == $team->id)>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 👇 فقط للـ Agent --}}

                <div class="mb-6">
                    <label for="assigned_to" class="block text-gray-700 font-semibold mb-2">{{ __('text.assigned_to') }}</label>
                    <select name="assigned_to" id="assigned_to"
                        class="shadow border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150">
                        <option value="">{{ __('text.select_agent') }}</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}" @selected(old('assigned_to') == $agent->id)>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- الحالة --}}
                <div class="mb-8">
                    <label for="status" class="block text-gray-700 font-semibold mb-2">{{ __('text.project_status') }}</label>
                    <select name="status" id="status"
                        class="shadow border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                        <option value="active" @selected(old('status') == 'active')>{{ __('text.status_active') }}</option>
                        <option value="pending" @selected(old('status') == 'pending')>{{ __('text.status_pending') }}</option>
                        <option value="completed" @selected(old('status') == 'completed')>{{ __('text.status_completed') }}</option>
                    </select>
                </div>

                {{-- زر الحفظ --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-700 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg focus:outline-none focus:shadow-outline">
                        <i class="fas fa-save me-2"></i> {{ __('text.save_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
