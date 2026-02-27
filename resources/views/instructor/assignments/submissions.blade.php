<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Submissions - {{ $assignment->title }}
            </h2>
            <x-button href="{{ route('instructor.assignments.index', $assignment->course) }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
                Kembali
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
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Submissions</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mahasiswa submissions</h3>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Mahasiswa</th>
                        <th class="px-4 py-3">Kirimted At</th>
                        <th class="px-4 py-3">Skor</th>
                        <th class="px-4 py-3">Aksis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($submissions as $submission)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $submission->student?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $submission->submitted_at?->format('Y-m-d H:i') ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $submission->score ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('instructor.submissions.grade', $submission) }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="score" value="{{ $submission->score ?? 0 }}" min="0" class="rounded-xl px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 w-24">
                                    <input type="text" name="feedback" value="{{ $submission->feedback }}" placeholder="Feedback" class="rounded-xl px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                    <x-button type="submit" size="sm" variant="success" icon="check">Nilai</x-button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="4">No submissions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
