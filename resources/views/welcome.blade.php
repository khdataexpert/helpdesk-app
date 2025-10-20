<x-app-layout>

    {{-- 💡 حل مشكلة الـ Design: إضافة <style> مخصصة لصفحة اللوجن فقط --}}
    {{-- الهدف هو إلغاء الـ Padding الزيادة (p-6) اللي جاي من الـ Layout الأساسي --}}
    <style>
        .login-wrapper>div {
            /* إلغاء الـ Padding اللي جاي من الـ Layout الأساسي عشان الخلفية تملأ الشاشة */
            padding: 0 !important;
        }
    </style>

    {{-- منطق الـ PHP لتحديد اللغة والتحويل عليها --}}
    @php
        // تحديد اللغة الحالية: بيتم سحبها من الـ Session بفضل الـ Middleware
        $currentLocale = app()->getLocale();

        // تحديد اللغة اللي هيتم التحويل عليها
        $newLocale = $currentLocale === 'ar' ? 'en' : 'ar';

        // تحديد النص اللي هيظهر على الزرار
        $buttonText = $newLocale === 'en' ? 'English' : 'العربية';
    @endphp

    {{-- زر تبديل اللغة (مثبت في أعلى اليمين) --}}
    <a href="{{ route('lang.switch', ['locale' => $newLocale]) }}"
        class="absolute top-4 right-4 text-sm font-bold px-4 py-2 rounded-lg 
     transition duration-300 shadow-md border-2 z-50"
        style="background-color: #40A8C4; border-color: #235784; color: white; 
     hover-bg: #235784; hover-border-color: #40A8C4;">

        {{ $buttonText }}
    </a>

    {{-- div الرئيسي لمركزة كارت اللوجن في المنتصف --}}
    {{-- 💡 التعديل هنا: استخدمنا h-screen بدل min-h-screen p-4 عشان نملأ الشاشة --}}
    {{-- 💡 وأضفنا p-4 داخل الـ div عشان الـ padding الداخلي --}}
    <div class="flex items-center justify-center h-screen p-4"
        style="background: linear-gradient(to bottom right, #F7AA00, #235784);">

        <div class="bg-white rounded-lg shadow-xl overflow-hidden max-w-md w-full">
            <div class="p-8 w-full">

                {{-- الشعار --}}
                <div class="flex justify-center mb-6">

                    {{-- الـ Container الدائري: بدون لون خلفية عشان الخلفية اللي وراه هي اللي تظهر --}}
                    <div class="w-24 h-24 rounded-full flex items-center justify-center overflow-hidden">

                        {{-- الصورة: هتظهر فوق الخلفية المتدرجة لصفحة اللوجن --}}
                        <img src="{{ asset('image/logo swedan.png') }}" alt="Logo"
                            class="w-full h-full object-contain">
                    </div>
                </div>

                {{-- العناوين --}}
                <h2 class="text-3xl font-extrabold text-center text-gray-800 mb-2">{{ __('text.welcome_title') }}</h2>
                <p class="text-center text-gray-600 mb-8">{{ __('text.please_login_message') }}</p>
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ $errors->frist()}}</span>
                    </div>
                @endif
                

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- حقل البريد الإلكتروني --}}
                    <div class="mb-5">
                        <label for="email"
                            class="block text-gray-700 text-sm font-semibold mb-2">{{ __('text.email_label') }}</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2"
                            placeholder="{{ __('text.enter_email_placeholder') }}"
                            style="focus-border-color: #235784; focus-ring-color: #23578450;" required>
                    </div>

                    {{-- حقل كلمة المرور --}}
                    <div class="mb-6">
                        <label for="password"
                            class="block text-gray-700 text-sm font-semibold mb-2">{{ __('text.password_label') }}</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2"
                            placeholder="{{ __('text.enter_password_placeholder') }}"
                            style="focus-border-color: #235784; focus-ring-color: #23578450;" required>
                    </div>

                    {{-- تذكرني فقط --}}
                    <div class="flex items-center mb-6">
                        <div class="flex items-center">
                            {{-- استخدام ms-2 (Margin Start) لدعم RTL/LTR --}}
                            <input type="checkbox" id="remember_me" name="remember"
                                class="h-4 w-4 text-orange-500 focus:ring-orange-400 border-gray-300 rounded"
                                style="color: #F7AA00;">
                            <label for="remember_me"
                                class="ms-2 block text-sm text-gray-900">{{ __('text.remember_me') }}</label>
                        </div>
                        {{-- ⚠️ تم حذف رابط نسيت كلمة المرور من هنا ⚠️ --}}
                    </div>

                    {{-- زر تسجيل الدخول --}}
                    <button type="submit"
                        class="w-full text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-opacity-50"
                        style="background-color: #F7AA00; hover-bg-color: #e09f00; focus-ring-color: #F7AA00;">
                        {{ __('text.login_btn') }}
                    </button>
                </form>

                {{-- ⚠️ تم حذف فقرة إنشاء حساب من هنا ⚠️ --}}
            </div>
        </div>
    </div>
</x-app-layout>
