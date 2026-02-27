<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Laporan
            </h2>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <x-button href="{{ route('instructor.reports.export.csv') }}" variant="secondary" icon="download" class="w-full sm:w-auto">
                    Export CSV
                </x-button>
                <x-button href="{{ route('instructor.reports.index') }}?print=1" variant="secondary" icon="printer" class="w-full sm:w-auto" onclick="window.print(); return false;">
                    Print / PDF
                </x-button>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Mata Kuliah</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $courses }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Tugas</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $assignments }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Submissions</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $submissions }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Dinilai</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $graded }}</h3>
        </x-card>
    </div>

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Summary</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Quick recap</h3>
            </div>
        </x-slot>
        <p class="text-sm text-gray-500">Use Export CSV or Print to save report.</p>
    </x-card>
</x-app-layout>
