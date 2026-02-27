<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Dasbor Dosen') }}
            </h2>
            <x-button href="{{ route('instructor.courses.index') }}" icon="book" class="w-full sm:w-auto">
                Mata Kuliah
            </x-button>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Total Mata Kuliah</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalCourses }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Total Mahasiswa</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalStudents }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Menunggu Penilaian</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $pendingGrading }}</h3>
        </x-card>
    </div>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Mata Kuliah</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Mata Kuliah</h3>
                </div>
                <x-button href="{{ route('instructor.courses.index') }}" size="sm" variant="secondary" icon="arrow-right">
                    Lihat Semua
                </x-button>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Mata Kuliah</th>
                        <th class="px-4 py-3">Mahasiswa</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($courses as $course)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $course->title }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $course->students_count }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                                    {{ $course->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $course->status }}
                                </span>
                            </td>
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
