<x-app-layout>

    @if (session('status'))
        <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl">
            {{ session('status') }}
        </div>
    @endif

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">All quizzes</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang all quizzes.</p>
                </div>
                <div class="flex sm:justify-end gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                    <x-button href="{{ route('instructor.quizzes.create') }}" data-drawer="true" icon="plus" class="w-full sm:w-auto">
                        Baru Quizzes
                    </x-button>
                </div>
            </div>
        </x-slot>

        <form method="GET" action="{{ route('instructor.quizzes.index') }}" class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-4 lg:col-span-5 w-full">
                    <x-input label="Search Title" name="q" type="text" icon="search" placeholder="Search by title..." value="{{ request('q') }}" />
                </div>
                <div class="md:col-span-5 lg:col-span-4 w-full">
                    <x-select name="status" label="Filter Status" icon="toggle-left" placeholder="All Statuses" onchange="this.form.submit()">
                        <option value="published" @selected(request('status') === 'published')>Published</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    </x-select>
                </div>
                <div class="md:col-span-3 lg:col-span-3 w-full flex items-center gap-2 sm:gap-3">
                    <x-button type="submit" icon="filter" class="flex-1 justify-center">Filter</x-button>
                    <x-button href="{{ route('instructor.quizzes.index') }}" variant="secondary" class="flex-1 justify-center">Reset</x-button>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Courses</th>
                        <th class="px-4 py-3">Published</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($quizzes as $quiz)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $quiz->title }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $quiz->course?->title ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                                    {{ $quiz->published ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $quiz->published ? 'yes' : 'no' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <x-button href="{{ route('instructor.quizzes.edit', $quiz) }}" data-drawer="true" size="sm" variant="secondary" icon="edit">
                                        Edit
                                    </x-button>
                                    <x-button href="{{ route('instructor.quizzes.attempts', $quiz) }}" size="sm" variant="secondary" icon="list-check">
                                        Attempts
                                    </x-button>
                                    <form method="POST" action="{{ route('instructor.quizzes.destroy', $quiz) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" size="sm" variant="danger" icon="trash"
                                            onclick="return confirm('Delete quiz?')">
                                            Delete
                                        </x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="4">No quizzes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div>
        {{ $quizzes->links() }}
    </div>
</x-app-layout>
