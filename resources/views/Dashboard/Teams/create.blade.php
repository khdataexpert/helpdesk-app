<x-app-layout>
    @php
        // تأكيد دعم الاتجاه
        $isRtl = app()->isLocale('ar');
        $textDirection = $isRtl ? 'text-right' : 'text-left';
        $labelClass = "block text-sm font-medium text-gray-700 mb-1 {$textDirection}";
        $inputClass = "mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm";
    @endphp

    <div class="p-6">
        <h2 class="text-3xl font-semibold mb-6 {{ $textDirection }} text-gray-800">
            {{ __('text.create_title') }}
        </h2>

        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-xl">
            <form action="{{ route('teams.store') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- اسم الفريق --}}
                <div>
                    <label for="name" class="{{ $labelClass }}">
                        {{ __('text.team_name') }}
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="{{ $inputClass }} @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- التخصص --}}
                <div>
                    <label for="Specialization" class="{{ $labelClass }}">
                        {{ __('text.team_specialization') }}
                    </label>
                    <input type="text" id="Specialization" name="Specialization" value="{{ old('Specialization') }}"
                           class="{{ $inputClass }} @error('Specialization') border-red-500 @enderror">
                    @error('Specialization')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- قائد الفريق (Team Leader) --}}
                <div>
                    <label for="user_id" class="{{ $labelClass }}">
                        {{ __('text.team_leader') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="user_id" name="user_id" required
                            class="{{ $inputClass }} @error('user_id') border-red-500 @enderror">
                        <option value="">{{ __('text.select_leader') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                                {{-- 💡 (Agent) إضافة كلمة (Agent) عشان تعرف مين ده --}}
                                @if ($user->hasRole('agent')) (Agent) @endif
                            </option>
                        @endforeach
                        {{-- 💡 رسالة في حالة عدم وجود Agents --}}
                        @if ($users->isEmpty())
                             <option value="" disabled>{{ __('text.no_agents_found') }}</option>
                        @endif
                    </select>
                    @error('user_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                

                {{-- أزرار الإجراءات --}}
                <div class="flex {{ $isRtl ? 'justify-end space-x-reverse' : 'justify-start' }} space-x-3 pt-4 ">
                    <button type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        {{ __('text.save_create') }}
                    </button>
                    <a href="{{ route('teams.index') }}" 
                        class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                        {{ __('text.back_to_list') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>