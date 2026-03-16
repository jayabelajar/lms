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
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Quizzes tersedia</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang quizzes tersedia.</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-button href="{{ route('student.my-courses') }}" variant="secondary" icon="book" class="w-full sm:w-auto">
                        Courses
                    </x-button>
                </div>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Quizzes</th>
                        <th class="px-4 py-3">Courses</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($quizzes as $quiz)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $quiz->title }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $quiz->course?->title ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if (in_array($quiz->id, $attempted, true))
                                    <x-badge size="sm" variant="success">Submitted</x-badge>
                                @else
                                    <x-badge size="sm" variant="warning">Menunggu</x-badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if (in_array($quiz->id, $attempted, true))
                                    <x-button href="{{ route('student.quizzes.review', $quiz) }}" size="sm" variant="secondary" icon="eye">
                                        Tinjau
                                    </x-button>
                                @else
                                    <x-button href="{{ route('student.quizzes.show', $quiz) }}" size="sm" variant="secondary" icon="arrow-right">
                                        Take Quizzes
                                    </x-button>
                                @endif
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
