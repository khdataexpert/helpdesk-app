@props(['icon', 'color', 'title', 'count', 'link' => '#'])

<a href="{{ $link }}" class="block bg-white shadow-md rounded-2xl p-6 hover:shadow-xl transition transform hover:-translate-y-1">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-gray-600 text-sm font-medium">{{ $title }}</h3>
            <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $count }}</p>
        </div>
        <div class="text-{{ $color }}-600 text-4xl">
            <i class="{{ $icon }}"></i>
        </div>
    </div>

    {{-- ✅ سهم صغير جميل تحت --}}
    <div class="mt-4 text-sm text-{{ $color }}-600 font-semibold flex items-center">
        <span>{{ __('View Details') }}</span>
        <i class="fas fa-arrow-right ml-2 text-xs"></i>
    </div>
</a>
