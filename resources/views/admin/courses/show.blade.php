<x-app-layout>
    <div x-data="{ showStudentDrawer: false }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Summary') }}
        </h2>
    </x-slot>

    @if (session('status'))
        <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <x-card class="lg:col-span-2">
            <x-slot name="header">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                    <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Summary</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang summary.</p>
                </div>
                    <div class="flex sm:justify-end gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                        <x-button @click="showStudentDrawer = true" type="button" icon="user-plus" class="w-full sm:w-auto">Add Student</x-button>
                        <x-button href="{{ route('admin.courses.edit', $course) }}" data-drawer="true" icon="edit" class="w-full sm:w-auto" variant="secondary">Edit Course</x-button>
                    </div>
                </div>
            </x-slot>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $course->slug }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $course->instructor?->name ?? '-' }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                    <span class="inline-flex mt-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                        {{ $course->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $course->status }}
                    </span>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Description</p>
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-200">
                        {{ $course->description ?: 'No description.' }}
                    </div>
                </div>
            </div>
        </x-card>

        <x-card>
            <x-slot name="header">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Enrolled Students</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang enrolled students.</p>
                </div>
            </x-slot>


            <ul class="space-y-3">
                @forelse ($course->students as $student)
                    <li class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $student->name }}</p>
                            <p class="text-xs text-gray-500">{{ $student->email }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-600">Aktif</span>
                            <form method="POST" action="{{ route('admin.courses.students.remove', [$course, $student]) }}">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" size="sm" variant="danger" icon="trash"
                                    onclick="return confirm('Delete students dari courses?')">
                                    Delete
                                </x-button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-gray-500">No students.</li>
                @endforelse
            </ul>
        </x-card>
    </div>

    <!-- Student Drawer -->
    <div x-show="showStudentDrawer" style="display: none;" class="relative z-[100]">
        <div x-transition.opacity class="fixed inset-0 bg-gray-900/80" @click="showStudentDrawer = false"></div>
        <div class="fixed inset-y-0 right-0 flex w-full sm:w-[500px] pointer-events-none">
            <div x-transition.transform="" class="w-full h-full flex flex-col bg-white dark:bg-gray-900 shadow-2xl pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add Student</h3>
                    <button type="button" @click="showStudentDrawer = false" class="text-gray-400 hover:text-gray-500">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto w-full flex-1">
                    <form method="POST" action="{{ route('admin.courses.students.add', $course) }}" class="space-y-4">
                        @csrf
                        <x-select name="student_id" label="Select Student" icon="users" required>
                            <option value="">Select students</option>
                            @foreach ($availableStudents as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                            @endforeach
                        </x-select>
                        <x-button type="submit" icon="plus" class="w-full">Add to Course</x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    </div>
</x-app-layout>
