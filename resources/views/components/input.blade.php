@props([
    'label' => null,
    'icon' => null,
    'name' => null,
    'type' => 'text',
])

<div class="w-full space-y-1" x-data="{ show: false }">

    {{-- Label --}}
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-500 dark:text-gray-300">
            {{ $label }}
        </label>
    @endif

    <div class="relative w-full flex items-center">

        {{-- Icon Prefix --}}
        @if($icon)
            <div class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center justify-center text-gray-400 dark:text-gray-400
                        group-focus-within:text-indigo-500 transition-colors pointer-events-none">
                <i class="ti ti-{{ $icon }} text-lg"></i>
            </div>
        @endif

        {{-- Input Field --}}
        <input
            :type="type === 'password' && show ? 'text' : '{{ $type }}'"
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $attributes->merge([
                'class' => "w-full h-12 text-sm font-semibold rounded-xl
                            bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-white
                            border border-gray-200 dark:border-gray-700
                            outline-none transition-all focus:ring-2 " .
                            ($icon ? 'pl-10 pr-12' : 'px-4 pr-12') .
                            ($errors->has($name)
                                ? ' focus:ring-rose-500/30 ring-1 ring-rose-500/50'
                                : ' focus:ring-indigo-500/30')
            ]) }}
            value="{{ old($name, $attributes->get('value')) }}"
        >

        {{-- Show/Hide Password Toggle --}}
        @if($type === 'password')
            <button type="button"
                    @click="show = !show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center text-gray-400 dark:text-gray-400
                           hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors"
            >
                <i :class="show ? 'ti ti-eye' : 'ti ti-eye-off'" class="text-lg"></i>
            </button>
        @endif

    </div>

    {{-- Error Message --}}
    @error($name)
        <p class="mt-1 text-[10px] font-bold text-rose-500 flex items-center gap-1">
            <i class="ti ti-alert-circle text-xs"></i> {{ $message }}
        </p>
    @enderror

</div>
