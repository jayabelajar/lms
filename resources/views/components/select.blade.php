@props([
    'label' => null,
    'icon' => null,
    'name' => null,
    'placeholder' => 'Pilih opsi...'
])

<div class="w-full space-y-2">

    {{-- Label --}}
    @if($label)
        <label for="{{ $name }}" class="block text-[11px] font-bold text-gray-400 dark:text-gray-300 uppercase ml-1 tracking-widest">
            {{ $label }}
        </label>
    @endif

    <div class="relative group w-full">

        {{-- Icon Prefix --}}
        @if($icon)
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-400 group-focus-within:text-indigo-500 transition-colors z-10 pointer-events-none">
                <i class="ti ti-{{ $icon }} text-lg"></i>
            </div>
        @endif

        {{-- Select Field --}}
        <select 
            name="{{ $name }}" 
            id="{{ $name }}"
            {{ $attributes->merge([
                'class' => "w-full rounded-2xl py-3 text-sm font-bold appearance-none outline-none
                            bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-white
                            border border-gray-200 dark:border-gray-700
                            transition-all focus:ring-2 " . 
                            ($icon ? 'pl-12 pr-10' : 'px-4 pr-10') . 
                            ($errors->has($name) 
                                ? 'focus:ring-rose-500/30 ring-1 ring-rose-500/50' 
                                : 'focus:ring-indigo-500/30')
            ]) }}
        >
            @if($placeholder)
                <option value="" disabled selected class="text-gray-400">{{ $placeholder }}</option>
            @endif

            {{ $slot }}
        </select>

        {{-- Custom Arrow Icon --}}
        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-400 group-focus-within:text-indigo-500 pointer-events-none transition-all duration-300">
            <i class="ti ti-chevron-down text-lg"></i>
        </div>

    </div>

    {{-- Error Message --}}
    @error($name)
        <p class="mt-1 text-[10px] font-bold text-rose-500 ml-1 uppercase tracking-tight flex items-center gap-1">
            <i class="ti ti-alert-circle"></i> {{ $message }}
        </p>
    @enderror

</div>
