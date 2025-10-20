<x-app-layout>
    <div class="container mx-auto p-4 md:p-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-2xl border border-gray-200">
            {{-- كان: text-[#235784] --}}
            <h1 class="text-3xl font-extrabold mb-8 text-gray-800 border-b pb-4 border-gray-100">
                {{ __('text.add_new_user') }}
            </h1>

            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                
                {{-- حقل الاسم --}}
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">{{ __('text.user_name') }}</label>
                    <input type="text" name="name" id="name" required 
                            {{-- كان: focus:ring-[#40A8C4] --}}
                           class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150 @error('name') border-red-500 @enderror"
                           value="{{ old('name') }}" placeholder="{{ __('text.enter_name_placeholder') }}">
                    @error('name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- حقل البريد الإلكتروني --}}
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">{{ __('text.user_email') }}</label>
                    <input type="email" name="email" id="email" required 
                           {{-- كان: focus:ring-[#40A8C4] --}}
                           class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150 @error('email') border-red-500 @enderror"
                           value="{{ old('email') }}" placeholder="{{ __('text.enter_email_placeholder') }}">
                    @error('email') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
                
                {{-- حقل كلمة المرور --}}
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">{{ __('text.password') }}</label>
                    <input type="password" name="password" id="password" required 
                            {{-- كان: focus:ring-[#40A8C4] --}}
                           class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150 @error('password') border-red-500 @enderror"
                           placeholder="{{ __('text.enter_password_placeholder') }}">
                    @error('password') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- حقل تأكيد كلمة المرور --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">{{ __('text.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                           {{-- كان: focus:ring-[#40A8C4] --}}
                           class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 transition duration-150">
                </div>

                {{-- حقل اختيار الدور --}}
                <div class="mb-8">
                    <label for="role" class="block text-gray-700 font-semibold mb-2">{{ __('text.user_role') }}</label>
                    <select name="role" id="role" required
                            {{-- كان: focus:ring-[#40A8C4] --}}
                            class="shadow border rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 @error('role') border-red-500 @enderror">
                        <option value="" disabled selected>{{ __('text.select_role_placeholder') }}</option>
                        @foreach ($roles as $name => $displayName)
                            <option value="{{ $name }}" @selected(old('role') === $name)>
                                {{ $displayName }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- زر الحفظ --}}
                <div class="flex justify-end">
                    <button type="submit" 
                            {{-- كان: bg-[#235784] hover:bg-[#40A8C4] --}}
                            class="bg-blue-700 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg focus:outline-none focus:shadow-outline">
                        @if (app()->isLocale('ar'))
                            <i class="fas fa-save ms-2"></i> {{ __('text.save_user_btn') }}
                        @else
                            <i class="fas fa-save me-2"></i> {{ __('text.save_user_btn') }}
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>