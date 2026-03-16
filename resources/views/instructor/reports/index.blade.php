<x-app-layout>
    

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Courses</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $courses }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Assignments</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $assignments }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Submissions</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $submissions }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Digrades</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $graded }}</h3>
        </x-card>
    </div>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Quick recap</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang quick recap.</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-button href="{{ route('instructor.reports.export.csv') }}" variant="secondary" icon="download" class="w-full sm:w-auto">
                        Export CSV
                    </x-button>
                </div>
            </div>
        </x-slot>
        <p class="text-sm text-gray-500">Use Export CSV or Print to save report.</p>
    </x-card>
</x-app-layout>
