<x-app-layout>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Students scores</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang students scores.</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-button href="{{ route('instructor.grades.export.csv') }}" variant="secondary" icon="download" class="w-full sm:w-auto">
                        Export CSV
                    </x-button>
                </div>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Students</th>
                        <th class="px-4 py-3">Courses</th>
                        <th class="px-4 py-3">Assignments</th>
                        <th class="px-4 py-3">Skor</th>
                        <th class="px-4 py-3">Submitted</th>
                        <th class="px-4 py-3">Digrades</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($submissions as $row)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $row->student?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $row->assignment?->course?->title ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $row->assignment?->title ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $row->score ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $row->submitted_at?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $row->graded_at?->format('Y-m-d') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="6">No grades.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div>
        {{ $submissions->links() }}
    </div>
</x-app-layout>
