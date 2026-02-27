@props([
    'size' => 'md',
    'withText' => true
])

@php
    // Konfigurasi skala ukuran
    $sizes = [
        'sm' => [
            'box' => 'w-8 h-8 rounded-xl',
            'icon' => 'text-xl',
            'text' => 'text-lg'
        ],
        'md' => [
            'box' => 'w-12 h-12 rounded-2xl',
            'icon' => 'text-3xl',
            'text' => 'text-xl'
        ],
        'lg' => [
            'box' => 'w-16 h-16 rounded-[1.8rem]',
            'icon' => 'text-4xl',
            'text' => 'text-2xl'
        ],
    ];

    $current = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    {{-- Ikon Logo --}}
    <div class="{{ $current['box'] }} bg-indigo-600 flex items-center justify-center text-white shadow-xl shadow-indigo-500/20 rotate-3 hover:rotate-0 transition-all duration-500 shrink-0">
        <i class="ti ti-school {{ $current['icon'] }}"></i>
    </div>

    {{-- Teks Brand --}}
    @if($withText)
        <h1 class="{{ $current['text'] }} font-bold text-gray-900 dark:text-white tracking-tight leading-none">
            {{ config('app.name', 'LMS') }} <span class="text-indigo-600">Dashboard</span>
        </h1>
    @endif
</div>
