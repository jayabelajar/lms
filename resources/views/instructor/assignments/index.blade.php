<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Tugas - {{ $course->title }}
            </h2>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <x-button href="{{ route('instructor.assignments.create', $course) }}" icon="plus" class="w-full sm:w-auto">
                    Baru Tugas
                </x-button>
                <x-button href="{{ route('instructor.courses.show', $course) }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
                    Kembali
                </x-button>
            </div>
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
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Due</th>
                        <th class="px-4 py-3">Submissions</th>
                        <th class="px-4 py-3">Aksis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($assignments as $assignment)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $assignment->title }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $assignment->due_at?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $assignment->submissions_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <x-button href="{{ route('instructor.assignments.submissions', $assignment) }}" size="sm" variant="secondary" icon="list-check">
                                        Submissions
                                    </x-button>
                                    <x-button href="{{ route('instructor.assignments.edit', $assignment) }}" size="sm" variant="secondary" icon="edit">
                                        Ubah
                                    </x-button>
                                    <form method="POST" action="{{ route('instructor.assignments.destroy', $assignment) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" size="sm" variant="danger" icon="trash"
                                            onclick="return confirm('Hapus assignment?')">
                                            Hapus
                                        </x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="4">Belum ada tugas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
