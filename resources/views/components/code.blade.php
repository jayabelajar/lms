@php
    /**
     * Ambil isi slot (aman untuk HtmlString & string biasa)
     */
    $code = $slot instanceof \Illuminate\Support\HtmlString
        ? $slot->toHtml()
        : (string) $slot;

    /**
     * 1. Hapus newline kosong di awal & akhir
     */
    $code = preg_replace('/^\s*\n|\n\s*$/', '', $code);

    /**
     * 2. Normalisasi indent
     *    Cari indent terkecil lalu hapus dari semua baris
     */
    $lines = preg_split("/\r\n|\n|\r/", $code);
    $minIndent = null;

    foreach ($lines as $line) {
        if (trim($line) === '') {
            continue;
        }

        preg_match('/^\s*/', $line, $match);
        $indent = strlen($match[0]);

        if ($minIndent === null || $indent < $minIndent) {
            $minIndent = $indent;
        }
    }

    if ($minIndent && $minIndent > 0) {
        $lines = array_map(function ($line) use ($minIndent) {
            return preg_replace('/^\s{0,' . $minIndent . '}/', '', $line);
        }, $lines);
    }

    $code = implode("\n", $lines);
@endphp

<div
    x-show="showCode"
    x-collapse
    x-cloak
    x-data="{ copied: false }"
    class="
        relative
        border-t border-gray-200 dark:border-gray-800
        bg-gradient-to-br from-gray-50 to-gray-100
        dark:from-gray-950 dark:to-gray-900
    "
>
    {{-- ================= Header ================= --}}
    <div
        class="
            flex items-center justify-between
            px-6 py-3
            text-xs font-semibold
            text-gray-500 dark:text-gray-400
            border-b border-gray-200 dark:border-gray-800
        "
    >
        <span class="uppercase tracking-wide">
            Blade Example
        </span>

        <div class="flex items-center gap-3">
            {{-- Copy Button --}}
            <button
                @click="
                    navigator.clipboard.writeText($refs.code.innerText);
                    copied = true;
                    setTimeout(() => copied = false, 1500);
                "
                class="
                    inline-flex items-center gap-1.5
                    px-2.5 py-1.5
                    rounded-lg
                    text-[11px]
                    font-semibold
                    text-gray-500 dark:text-gray-400
                    hover:bg-gray-200 dark:hover:bg-gray-800
                    transition
                "
            >
                <i class="ti ti-copy text-sm"></i>
                <span x-show="!copied">Copy</span>
                <span x-show="copied" class="text-emerald-500">Copied!</span>
            </button>

            {{-- Window Dots --}}
            <div class="flex gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
            </div>
        </div>
    </div>

    {{-- ================= Code ================= --}}
    <pre
        class="
            max-w-full
            p-6
            overflow-x-auto
            overscroll-x-contain
            text-[13px]
            leading-relaxed
            font-mono
            text-slate-700 dark:text-slate-300
            whitespace-pre-wrap
        "
    ><code x-ref="code">{{ $code }}</code></pre>
</div>
