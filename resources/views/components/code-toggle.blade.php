<button
    type="button"
    @click="showCode = !showCode"
    class="
        text-[10px]
        font-bold
        uppercase
        tracking-wider
        font-mono
        text-gray-400
        hover:text-indigo-600
        dark:hover:text-indigo-400
        transition-colors
        flex
        items-center
        gap-1
        select-none
    "
>
    <i
        class="ti"
        :class="showCode ? 'ti-eye-off' : 'ti-code'"
    ></i>

    <span x-text="showCode ? 'Hide Code' : 'Show Code'"></span>
</button>
