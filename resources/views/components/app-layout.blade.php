@php
    $locale = app()->getLocale();
    $dir = $locale === 'ar' ? 'rtl' : 'ltr';
    $isRtl = ($locale === 'ar');
@endphp

<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('text.app_name') }}</title>

    <link href="{{ asset('assets/img/Logo.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" />
</head>

<body class="index-page bg-gray-100 min-h-screen">
    @auth
        {{-- 💡 استخدمنا flex لتخلي الـ aside والـ content جنب بعض --}}
        <div class="flex min-h-screen">
            {{-- 💡 الـ aside ثابت في الجنب --}}
            <x-aside class="w-64 fixed top-0 {{ $isRtl ? 'right-0' : 'left-0' }} h-full bg-white shadow-lg z-40" />

            {{-- 💡 المحتوى (navbar + الصفحة) --}}
            <div class="flex-1 flex flex-col {{ $isRtl ? 'mr-64' : 'ml-64' }}">
                <x-nav class="fixed top-0 left-0 right-0 z-30" />

                {{-- 💡 المحتوى الرئيسي --}}
                <main class="pt-16 p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @endauth
        @guest
        {{-- لو مش مسجل دخول --}}
        <div class="">
            {{ $slot }}
        </div>
        @endguest
</body>
</html>
