<x-app-layout>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Select courses</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang select courses.</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-button href="{{ route('instructor.students.export.csv', ['course_id' => $selectedCourse->id]) }}" variant="secondary" icon="download" class="w-full sm:w-auto">
                        Export CSV
                    </x-button>
                </div>
            </div>
        </x-slot>
        <form method="GET" action="{{ route('instructor.students.index') }}" class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-8 lg:col-span-9 w-full">
                    <x-select name="course_id" label="Filter Course" icon="book" placeholder="Select a Course" required onchange="this.form.submit()">
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @selected($selectedCourse?->id === $course->id)>{{ $course->title }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="md:col-span-4 lg:col-span-3 w-full flex items-center gap-2 sm:gap-3">
                    <x-button type="submit" icon="filter" class="flex-1 justify-center">Filter</x-button>
                </div>
            </div>
        </form>
    </x-card>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Students Courses</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang students courses.</p>
                </div>
                <div class="flex items-center gap-3">
                    
                </div>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Students</th>
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
                            <td class="px-4 py-6 text-center text-gray-500" colspan="3">No students.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
