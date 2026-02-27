@props([
    'title' => null,
    'image' => null,
    'padding' => true,
])

@php
    use Illuminate\Support\Str;
    $class = $attributes->get('class', '');
    $hasBg = Str::contains($class, 'bg-');
    $hasBorder = Str::contains($class, 'border-');
@endphp

<div {{ $attributes->merge([
    'class' => collect([
        'relative overflow-hidden rounded-3xl',
        'transition-all duration-300',
        $hasBg ? null : 'bg-white dark:bg-gray-900',
        $hasBorder ? null : 'border border-gray-100 dark:border-gray-800',
    ])->filter()->join(' ')
]) }}>

    {{-- Image --}}
    @if($image)
        <div class="relative overflow-hidden aspect-video bg-gray-100 dark:bg-gray-800">
            <img
                src="{{ $image }}"
                alt="{{ $title }}"
                class="w-full h-full object-cover"
            >
        </div>
    @endif

    {{-- Header --}}
    @if(isset($header) || $title)
        <div class="flex items-center justify-between
                    px-8 py-6
                    border-b border-gray-100 dark:border-gray-800">
            @if(isset($header))
                {{ $header }}
            @else
                <h3 class="text-base font-bold
                           text-gray-900 dark:text-gray-100">
                    {{ $title }}
                </h3>
            @endif
        </div>
    @endif

    {{-- Body --}}
    <div @class([
        'relative z-10',
        'p-8' => $padding,
        'p-0' => !$padding,
    ])>
        {{ $slot }}
    </div>

    {{-- Footer --}}
    @if(isset($footer))
        <div class="px-8 py-6
                    bg-gray-50 dark:bg-gray-800/50
                    border-t border-gray-100 dark:border-gray-800">
            {{ $footer }}
        </div>
    @endif

</div>
