<x-app-layout>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Courses progress</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang courses progress.</p>
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
                        <th class="px-4 py-3">Courses</th>
                        <th class="px-4 py-3">Progres</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($progressRows as $row)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $row['course']->title }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $row['percent'] }}%</td>
                            <td class="px-4 py-3">
                                <x-badge size="sm" variant="{{ $row['status'] === 'Lulus' ? 'success' : 'warning' }}">
                                    {{ $row['status'] }}
                                </x-badge>
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

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <x-card>
            <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat Grades</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang riwayat grades.</p>
                </div>
                <div class="flex items-center gap-3">
                    
                </div>
            </div>
        </x-slot>

            <div class="overflow-x-auto w-full">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Assignments</th>
                            <th class="px-4 py-3">Courses</th>
                            <th class="px-4 py-3">Skor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($grades as $grade)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $grade->assignment?->title ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $grade->assignment?->course?->title ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $grade->score ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-gray-500" colspan="3">No grades.</td>
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
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Quizzes history</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang quizzes history.</p>
                </div>
                <div class="flex items-center gap-3">
                    
                </div>
            </div>
        </x-slot>

            <div class="overflow-x-auto w-full">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Quizzes</th>
                            <th class="px-4 py-3">Courses</th>
                            <th class="px-4 py-3">Skor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($quizScores as $attempt)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $attempt->quiz?->title ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $attempt->quiz?->course?->title ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $attempt->score ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-gray-500" colspan="3">No percobaan quizzes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-app-layout>
