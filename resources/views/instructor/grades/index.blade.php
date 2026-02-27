<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Nilai
            </h2>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <x-button href="{{ route('instructor.grades.export.csv') }}" variant="secondary" icon="download" class="w-full sm:w-auto">
                    Export CSV
                </x-button>
                <x-button href="{{ route('instructor.grades.index') }}?print=1" variant="secondary" icon="printer" class="w-full sm:w-auto" onclick="window.print(); return false;">
                    Print / PDF
                </x-button>
            </div>
        </div>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Nilai</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mahasiswa scores</h3>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Mahasiswa</th>
                        <th class="px-4 py-3">Mata Kuliah</th>
                        <th class="px-4 py-3">Tugas</th>
                        <th class="px-4 py-3">Skor</th>
                        <th class="px-4 py-3">Kirimted</th>
                        <th class="px-4 py-3">Dinilai</th>
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
                            <td class="px-4 py-6 text-center text-gray-500" colspan="6">Belum ada nilai.</td>
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
