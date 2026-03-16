<x-app-layout>
    <div x-data="{ showAddDrawer: false }">


    @if (session('status'))
        <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl">
            {{ session('status') }}
        </div>
    @endif


    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Across all courses</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang across all courses.</p>
                </div>
                <div class="flex sm:justify-end gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                    <x-button type="button" @click="showAddDrawer = true" icon="plus" class="w-full sm:w-auto">Add Assignment</x-button>
                </div>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Courses</th>
                        <th class="px-4 py-3">Due</th>
                        <th class="px-4 py-3">Submissions</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($assignments as $assignment)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $assignment->title }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $assignment->course?->title ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $assignment->due_at?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $assignment->submissions_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <x-button href="{{ route('instructor.assignments.submissions', $assignment) }}" size="sm" variant="secondary" icon="list-check">
                                        Submissions
                                    </x-button>
                                    <x-button href="{{ route('instructor.assignments.edit', $assignment) }}" data-drawer="true" size="sm" variant="secondary" icon="edit">
                                        Edit
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="5">No assignments.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div>
        {{ $assignments->links() }}
    </div>

    <!-- Add Drawer -->
    <div x-show="showAddDrawer" style="display: none;" class="relative z-[100]">
        <div x-transition.opacity class="fixed inset-0 bg-gray-900/80" @click="showAddDrawer = false"></div>
        <div class="fixed inset-y-0 right-0 flex w-full sm:w-[500px] pointer-events-none">
            <div x-transition.transform="" class="w-full h-full flex flex-col bg-white dark:bg-gray-900 shadow-2xl pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add Assignment</h3>
                    <button type="button" @click="showAddDrawer = false" class="text-gray-400 hover:text-gray-500">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto w-full flex-1">
                    <form method="POST" action="{{ route('instructor.assignments.quick-store') }}" class="space-y-4">
                        @csrf
                        <x-select name="course_id" label="Course" icon="book" required>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </x-select>
                        <x-input name="title" label="Assignment Title" icon="list" required />
                        <x-textarea name="description" label="Description" icon="notes" rows="4"></x-textarea>
                        <x-input label="Due Date" name="due_at" type="date" icon="calendar" />
                        <x-input label="Skor Maks" name="max_score" type="number" icon="award" value="100" />
                        <x-button type="submit" icon="check" class="w-full">Save Assignment</x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    </div>
</x-app-layout>
