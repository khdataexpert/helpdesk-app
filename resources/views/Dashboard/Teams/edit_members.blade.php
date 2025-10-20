<x-app-layout>
    @php
        $isRtl = app()->isLocale('ar');
        $textDirection = $isRtl ? 'text-right' : 'text-left';
    @endphp

    <div class="p-6">
        <h2 class="text-3xl font-semibold mb-2 {{ $textDirection }} text-gray-800">
            {{ __('text.edit_members_title') }} 
            <span class="text-blue-600">{{ $team->name }}</span>
        </h2>
        <p class="text-gray-600 mb-6 {{ $textDirection }}">
            {{ __('text.edit_members_subtitle') }}
        </p>

        <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
            <form action="{{ route('teams.update_members', $team) }}" method="POST">
                @csrf
                @method('PUT') 

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($users as $user)
                        <div class="p-3 border rounded-lg hover:bg-gray-50 transition duration-100">
                            <div class="flex items-center space-x-3 {{ $isRtl ? 'space-x-reverse' : '' }}">
                                {{-- ✅ لون Tailwind الافتراضي للأزرق --}}
                                <input 
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                                    type="checkbox" 
                                    name="members[]" 
                                    value="{{ $user->id }}" 
                                    id="user_{{ $user->id }}"
                                    @if (in_array($user->id, $currentMembers)) checked @endif
                                >
                                <label class="text-sm font-medium text-gray-700 cursor-pointer" for="user_{{ $user->id }}">
                                    {{ $user->name }} 
                                    <span class="text-xs text-gray-500">({{ $user->email }})</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ✅ الأزرار بألوان Tailwind الطبيعية --}}
                <div class="flex {{ $isRtl ? 'justify-end space-x-reverse' : 'justify-start' }} space-x-3 pt-6 border-t mt-6">
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                        {{ __('text.save_members') }}
                    </button>
                    <a href="{{ route('teams.index') }}" 
                       class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-100 transition duration-150">
                        {{ __('text.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
