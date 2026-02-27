@props([
    'label' => null,
    'icon' => null,
    'name' => null,
    'rows' => 4
])

<div class="w-full space-y-1">

    {{-- Label --}}
    @if($label)
        <label for="{{ $name }}" class="block text-[11px] font-bold text-gray-500 dark:text-gray-300 uppercase tracking-widest">
            {{ $label }}
        </label>
    @endif

    <div class="relative w-full flex items-start">

        {{-- Icon Prefix --}}
        @if($icon)
            <div class="absolute top-3 left-3 flex items-center justify-center text-gray-400 dark:text-gray-400
                        group-focus-within:text-indigo-500 transition-colors pointer-events-none">
                <i class="ti ti-{{ $icon }} text-lg"></i>
            </div>
        @endif

        {{-- Textarea Field --}}
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            {{ $attributes->merge([
                'class' => "w-full rounded-2xl py-3 text-sm font-semibold bg-gray-50 dark:bg-gray-800
                            text-gray-700 dark:text-white border border-gray-200 dark:border-gray-700
                            outline-none transition-all focus:ring-2 resize-none " .
                            ($icon ? 'pl-12 pr-4' : 'px-4') .
                            ($errors->has($name)
                                ? ' focus:ring-rose-500/30 ring-1 ring-rose-500/50'
                                : ' focus:ring-indigo-500/30')
            ]) }}
        >{{ old($name, $slot ?? '') }}</textarea>

    </div>

    {{-- Error Message --}}
    @error($name)
        <p class="mt-1 text-[10px] font-bold text-rose-500 flex items-center gap-1">
            <i class="ti ti-alert-circle text-xs"></i> {{ $message }}
        </p>
    @enderror

</div>
