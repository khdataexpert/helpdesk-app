<x-app-layout>
    <div class="container mx-auto p-4 md:p-8">
        {{-- ✅ Alerts --}}
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
                {{ __('text.create_ticket') }}
            </h1>

            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf

                {{-- العنوان --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.ticket_title') }}</label>
                    <input type="text" name="title" value="{{ old('title') }}" required 
                           class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- الوصف --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.ticket_description') }}</label>
                    <textarea name="description" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>

                {{-- المشروع --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.project_name') }}</label>
                    <select name="project_id" class="w-full border rounded-lg px-4 py-3">
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- الفريق --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.project_team') }}</label>
                    <select name="team_id" class="w-full border rounded-lg px-4 py-3">
                        <option value="">{{ __('text.none') }}</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.assigned_to') }}</label>
                    <select name="assigned_to" class="w-full border rounded-lg px-4 py-3">
                        <option value="">{{ __('text.none') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.ticket_type') }}</label>
                    <select name="type" class="w-full border rounded-lg px-4 py-3">
                        <option value="task">{{ __('text.type_task') }}</option>
                        <option value="bug">{{ __('text.type_bug') }}</option>
                        <option value="feature">{{ __('text.type_feature') }}</option>
                        <option value="improvement">{{ __('text.type_improvement') }}</option>
                    </select>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('text.ticket_priority') }}</label>
                    <select name="priority" class="w-full border rounded-lg px-4 py-3">
                        <option value="low">{{ __('text.priority_low') }}</option>
                        <option value="medium">{{ __('text.priority_medium') }}</option>
                        <option value="high">{{ __('text.priority_high') }}</option>
                        <option value="urgent">{{ __('text.priority_urgent') }}</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-700 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-300">
                        {{ __('text.save_ticket_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
