<x-app-layout>
    

    <div class="grid grid-cols-1 sm:grid-cols-3 xl:grid-cols-3 gap-6">
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Total Courses</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalCourses }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Total Instructors</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalInstructors }}</h3>
        </x-card>
        <x-card>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Total Students</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalStudents }}</h3>
        </x-card>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <x-card class="xl:col-span-2">
            <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Updates</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang recent updates.</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-button href="{{ route('admin.courses.index') }}" size="sm" variant="secondary" icon="arrow-right">
                        View All
                    </x-button>
                
                    <x-button href="{{ route('admin.courses.index') }}" icon="book" class="w-full sm:w-auto">
                Manage Courses
            </x-button>
                </div>
            
            </div>
        </x-slot>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Instructors</th>
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
                                <td class="px-4 py-6 text-center text-gray-500" colspan="3">No courses.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-card>
            <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Course Status</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang course status.</p>
                </div>
                <div class="flex items-center gap-3">
                    
                </div>
            </div>
        </x-slot>
            <div class="space-y-4">
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                    <p class="text-2xl font-bold text-emerald-600">{{ $publishedCourses }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                    <p class="text-2xl font-bold text-amber-600">{{ $draftCourses }}</p>
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>

