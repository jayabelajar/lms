<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Tugas
            </h2>
            <x-button href="{{ route('student.my-courses') }}" variant="secondary" icon="book" class="w-full sm:w-auto">
                Mata Kuliah
            </x-button>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl">
            {{ session('status') }}
        </div>
    @endif

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Tugas</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">All tasks</h3>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Tugas</th>
                        <th class="px-4 py-3">Mata Kuliah</th>
                        <th class="px-4 py-3">Due</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($assignments as $assignment)
                        @php $submission = $submissions[$assignment->id] ?? null; @endphp
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $assignment->title }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $assignment->course?->title ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $assignment->due_at?->format('d M Y') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if ($submission)
                                    <x-badge size="sm" variant="{{ $submission->graded_at ? 'success' : 'info' }}">
                                        {{ $submission->graded_at ? 'Dinilai' : 'Kirimted' }}
                                    </x-badge>
                                @else
                                    <x-badge size="sm" variant="warning">Menunggu</x-badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <x-button href="{{ route('student.assignments.show', $assignment) }}" size="sm" variant="secondary" icon="arrow-right">
                                    Buka
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="5">Belum ada tugas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div>
        {{ $assignments->links() }}
    </div>
</x-app-layout>
