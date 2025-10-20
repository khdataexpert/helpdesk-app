@php
    // جلب بيانات المستخدم المسجل
    $user = Auth::user();
    
    // 💡 منطق تحديد اللغة والتحويل عليها
    $currentLocale = app()->getLocale();
    $newLocale = $currentLocale === 'ar' ? 'en' : 'ar';
    $buttonText = $newLocale === 'en' ? 'English' : 'العربية';
@endphp

<nav class="bg-white shadow-md sticky top-0 z-40" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- 1. يسار (الشعار) --}}
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    {{-- ملاحظة: تم تعديل حجم اللوجو لـ h-10 ليتناسب مع ارتفاع الـ Navbar --}}
                    <img class="block h-24 w-auto" src="{{ asset('image/Logo.png') }}" alt="{{ __('text.logo') }}">
                </div>
            </div>

            {{-- 2. يمين (معلومات المستخدم وزرار الخروج وتبديل اللغة) --}}
            {{-- space-x-4 يستخدم المسافات العادية، و space-x-reverse بيعكس ترتيبها للعربي --}}
            <div class="flex items-center space-x-4 {{ app()->isLocale('ar') ? 'space-x-reverse' : '' }}">
                
                {{-- زر تبديل اللغة (جديد) --}}
                <a href="{{ route('lang.switch', ['locale' => $newLocale]) }}"
                    class="text-sm font-bold px-3 py-1 rounded-md transition duration-300 border"
                    style="border-color: #40A8C4; color: #235784; background-color: #E8F6FA;">
                    {{ $buttonText }}
                </a>

                {{-- اسم المستخدم المسجل (تم استخدام مفتاح welcome_title) --}}
                <span class="text-gray-700 font-semibold text-sm">
                    {{-- 💡 لاحظ: دمجنا "مرحباً" مع "اسم المستخدم" باستخدام مفتاح welcome_title --}}
                    {{ __('text.welcome_title') }}، {{ $user->name }}
                </span>

                {{-- زر تسجيل الخروج (Logout) --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="px-3 py-1 text-sm font-medium rounded-md transition duration-150 ease-in-out"
                            style="background-color: #F7AA00; color: white; hover-bg: #e09f00;">
                        {{ __('text.logout_btn') }} {{-- ⬅️ تم إضافة مفتاح ترجمة هنا --}}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>