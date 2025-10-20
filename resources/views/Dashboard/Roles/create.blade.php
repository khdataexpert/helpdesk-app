<x-app-layout>
    <div class="container mx-auto p-4 md:p-8">
        
        {{-- البطاقة الرئيسية --}}
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-2xl border border-gray-200">
            <h1 class="text-3xl font-extrabold mb-8 text-gray-800 border-b pb-4 border-gray-100">
                {{ __('text.create_new_role') }}
                </h1>

            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                
                {{-- 1. اسم الدور --}}
                <div class="mb-6">
                    <label for="name"
                        class="block text-gray-700 font-semibold mb-2">{{ __('text.role_name') }}</label>
                    <input type="text" name="name" id="name" required
                        
                        class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}"
                        placeholder="{{ __('text.enter_role_name_placeholder') }}">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                    
                </div>

                {{-- 2. تحديد الصلاحيات --}}
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-xl font-semibold text-gray-800">
                        {{ __('text.permissions_title') }}</h4>
                    
                    {{-- الزر: تحديد/إلغاء تحديد الكل --}}
                    <button type="button" id="toggle-all-btn"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-800 focus:outline-none transition duration-150 ease-in-out">
                        {{ __('text.select_all_btn') }}
                        </button>
                    </div>

                {{-- شبكة الصلاحيات --}}
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-8 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    @foreach ($permissions as $permission)
                        <div class="flex items-center p-2 rounded-md hover:bg-white transition duration-100">
                            <input type="checkbox" name="permissions[]"
                                value="{{ $permission->name }}" id="perm-{{ $permission->id }}"
                                
                                class="permission-checkbox h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                @checked(in_array($permission->name, old('permissions', [])))>
                            
                            {{-- ضبط الـ Margin بناءً على الاتجاه --}}
                            <label for="perm-{{ $permission->id }}"
                                class="text-sm text-gray-700 font-medium 
                                {{ app()->isLocale('ar') ? 'mr-3' : 'ml-3' }}">
                                {{ __("text.permission_{$permission->name}") }}
                                </label>
                            </div>
                    @endforeach
                    @error('permissions')
                        <p class="text-red-500 text-xs italic mt-2 col-span-full">{{ $message }}</p>
                    @enderror
                    
                </div>

                {{-- زر الحفظ --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg focus:outline-none focus:shadow-outline">
                        <i
                            class="fas fa-save {{ app()->isLocale('ar') ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('text.save_role_btn') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    {{-- كود JavaScript... (يبقى كما هو) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-all-btn');
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            let allChecked = false;

            function updateButtonText() {
                const totalCount = checkboxes.length;
                const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;

                if (totalCount === checkedCount) {
                    allChecked = true;
                    toggleBtn.textContent = '{{ __('text.unselect_all_btn') }}';
                } else {
                    allChecked = false;
                    toggleBtn.textContent = '{{ __('text.select_all_btn') }}';
                }
            }

            toggleBtn.addEventListener('click', function() {
                allChecked = !allChecked;
                checkboxes.forEach(checkbox => {
                    checkbox.checked = allChecked;
                });
                updateButtonText();
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateButtonText);
            });

            updateButtonText();
        });
    </script>
</x-app-layout>
