<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Dasbor Admin') }}
            </h2>
            <x-button href="{{ route('admin.courses.index') }}" icon="book" class="w-full sm:w-auto">
                Kelola Mata Kuliah
            </x-button>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Total Mata Kuliah</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalCourses }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Total Dosen</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalInstructors }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Total Mahasiswa</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalStudents }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Total Enrolmen</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalEnrollments }}</h3>
        </x-card>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <x-card class="xl:col-span-2">
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Mata Kuliah Terbaru</p>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pembaruan Terbaru</h3>
                    </div>
                    <x-button href="{{ route('admin.courses.index') }}" size="sm" variant="secondary" icon="arrow-right">
                        Lihat Semua
                    </x-button>
                </div>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Dosen</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($recentCourses as $course)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $course->title }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $course->instructor?->name ?? '-' }}</td>
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

        <x-card>
            <x-slot name="header">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Statistik</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Status Mata Kuliah</h3>
                </div>
            </x-slot>
            <div class="space-y-4">
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Terbit</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $publishedCourses }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Draf</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $draftCourses }}</p>
                </div>
                <x-button href="{{ route('admin.users.index') }}" variant="secondary" icon="users" class="w-full">
                    Kelola Pengguna
                </x-button>
                <x-button href="{{ route('admin.enrollments.index') }}" variant="secondary" icon="list-check" class="w-full">
                    Kelola Enrolmen
                </x-button>
            </div>
        </x-card>
    </div>

    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Enrolmen Terbaru</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mahasiswa terbaru</h3>
                </div>
                <x-button href="{{ route('admin.enrollments.index') }}" size="sm" variant="secondary" icon="arrow-right">
                    Lihat Semua
                </x-button>
            </div>
        </x-slot>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Mahasiswa</th>
                        <th class="px-4 py-3">Mata Kuliah</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($recentEnrollments as $enrollment)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $enrollment->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $enrollment->course?->title ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                                    {{ $enrollment->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $enrollment->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="3">Belum ada enrolmen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>

