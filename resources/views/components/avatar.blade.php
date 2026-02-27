@props([
    'src' => null,
    'name' => null,
    'size' => 'md',
    'shape' => 'xl',
    'status' => null,
])

@php
    $sizes = [
        'xs' => ['box' => 'w-7 h-7', 'text' => 'text-[10px]', 'dot' => 'w-2 h-2'],
        'sm' => ['box' => 'w-9 h-9', 'text' => 'text-xs', 'dot' => 'w-2.5 h-2.5'],
        'md' => ['box' => 'w-11 h-11', 'text' => 'text-sm', 'dot' => 'w-3 h-3'],
        'lg' => ['box' => 'w-14 h-14', 'text' => 'text-base', 'dot' => 'w-4 h-4'],
        'xl' => ['box' => 'w-20 h-20', 'text' => 'text-xl', 'dot' => 'w-5 h-5'],
    ];

    $statusColors = [
        'online'  => 'bg-emerald-500',
        'busy'    => 'bg-rose-500',
        'offline' => 'bg-gray-400',
        'away'    => 'bg-amber-500',
    ];

    $avatarSize = $sizes[$size] ?? $sizes['md'];
    $initials = '';
    if ($name) {
        $words = explode(' ', $name);
        $initials = count($words) > 1 
            ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
            : strtoupper(substr($words[0], 0, 2));
    }
@endphp

<div {{ $attributes->merge(['class' => 'relative inline-flex shrink-0']) }}>
    {{-- Avatar Box --}}
    <div class="{{ $avatarSize['box'] }} rounded-{{ $shape }} border border-gray-100 dark:border-gray-800
                bg-gray-50 dark:bg-gray-900 flex items-center justify-center overflow-hidden
                transition-transform duration-300 hover:scale-105">
        
        @if($src)
            <img src="{{ $src }}" alt="{{ $name }}" class="w-full h-full object-cover">
        @elseif($name)
            <span class="{{ $avatarSize['text'] }} font-bold text-indigo-600 dark:text-indigo-400 tracking-tighter">
                {{ $initials }}
            </span>
        @else
            <i class="ti ti-user {{ $avatarSize['text'] }} text-gray-400"></i>
        @endif
    </div>

    @if($status)
        <span class="absolute -bottom-0.5 -right-0.5 {{ $avatarSize['dot'] }} 
                     {{ $statusColors[$status] ?? 'bg-gray-400' }} 
                     border-2 border-white dark:border-gray-950 rounded-full"></span>
    @endif
</div>
