<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Mahasiswa
            </h2>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                @if($selectedCourse)
                    <x-button href="{{ route('instructor.students.export.csv', ['course_id' => $selectedCourse->id]) }}" variant="secondary" icon="download" class="w-full sm:w-auto">
                        Export CSV
                    </x-button>
                @endif
            </div>
        </div>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Filter</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pilih mata kuliah</h3>
            </div>
        </x-slot>
        <form method="GET" action="{{ route('instructor.students.index') }}" class="space-y-4">
            <x-select name="course_id" label="Mata Kuliah" icon="book" required>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected($selectedCourse?->id === $course->id)>{{ $course->title }}</option>
                @endforeach
            </x-select>
            <x-button type="submit" icon="filter">Terapkan</x-button>
        </form>
    </x-card>

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Peserta</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mahasiswa Mata Kuliah</h3>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Mahasiswa</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Progres</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($students as $student)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $student->name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $student->email }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $progressByStudent[$student->id] ?? 0 }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="3">Belum ada mahasiswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
