<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Dasbor Mahasiswa') }}
            </h2>
            <x-button href="{{ route('student.my-courses') }}" icon="book" class="w-full sm:w-auto">
                Mata Kuliah
            </x-button>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Mata Kuliah</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalCourses }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Overall Progres</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $progressPercent }}%</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Tugas Jatuh Tempo</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $assignmentDue }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Kuis Menunggu</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $quizPending }}</h3>
        </x-card>
    </div>

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Progres per Mata Kuliah</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Progres belajar</h3>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Mata Kuliah</th>
                        <th class="px-4 py-3">Selesai</th>
                        <th class="px-4 py-3">Progres</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($courses as $course)
                        @php
                            $done = (int) ($completedByCourse[$course->id] ?? 0);
                            $total = $course->materials_count;
                            $percent = $total > 0 ? (int) round(($done / $total) * 100) : 0;
                        @endphp
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $course->title }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $done }} / {{ $total }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $percent }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="3">Belum ada mata kuliah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
