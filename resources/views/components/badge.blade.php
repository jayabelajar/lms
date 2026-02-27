@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'soft',
    'rounded' => true,
    'icon' => null
])

@php
    // Konfigurasi Ukuran
    $sizes = [
        'sm' => 'px-1.5 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm',
        'lg' => 'px-3 py-1.5 text-md',
    ];

    // Konfigurasi Gaya berdasarkan Varian dan Tipe
    $variants = [
        'primary' => [
            'solid' => 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/20',
            'soft' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
            'outline' => 'border border-indigo-200 text-indigo-600 dark:border-indigo-500/30 dark:text-indigo-400',
        ],
        'success' => [
            'solid' => 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20',
            'soft' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
            'outline' => 'border border-emerald-200 text-emerald-600 dark:border-emerald-500/30 dark:text-emerald-400',
        ],
        'danger' => [
            'solid' => 'bg-rose-500 text-white shadow-sm shadow-rose-500/20',
            'soft' => 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400',
            'outline' => 'border border-rose-200 text-rose-600 dark:border-rose-500/30 dark:text-rose-400',
        ],
        'warning' => [
            'solid' => 'bg-amber-500 text-white shadow-sm shadow-amber-500/20',
            'soft' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
            'outline' => 'border border-amber-200 text-amber-600 dark:border-amber-500/30 dark:text-amber-400',
        ],
        'info' => [
            'solid' => 'bg-sky-500 text-white shadow-sm shadow-sky-500/20',
            'soft' => 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400',
            'outline' => 'border border-sky-200 text-sky-600 dark:border-sky-500/30 dark:text-sky-400',
        ],
        'gray' => [
            'solid' => 'bg-gray-600 text-white shadow-sm shadow-gray-500/20',
            'soft' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
            'outline' => 'border border-gray-200 text-gray-600 dark:border-gray-700 dark:text-gray-400',
        ],
    ];

    $currentStyle = $variants[$variant][$type] ?? $variants['primary']['soft'];
    $currentSize = $sizes[$size] ?? $sizes['md'];
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center justify-center font-semibold transition-all duration-200 " . 
               $currentStyle . " " . $currentSize . " " . ($rounded ? 'rounded-lg' : 'rounded-none')
]) }}>
    @if($icon)
        <i class="ti ti-{{ $icon }} {{ $size === 'sm' ? 'mr-0.5' : 'mr-1.5' }} {{ $size === 'lg' ? 'text-sm' : 'text-[1.1em]' }}"></i>
    @endif
    {{ $slot }}
</span>