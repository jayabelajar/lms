<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Attempts - {{ $quiz->title }}
            </h2>
            <x-button href="{{ route('instructor.quizzes.edit', $quiz) }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
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
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Attempts</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mahasiswa attempts</h3>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Mahasiswa</th>
                        <th class="px-4 py-3">Kirimted</th>
                        <th class="px-4 py-3">Skor</th>
                        <th class="px-4 py-3">Dinilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($attempts as $attempt)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $attempt->student?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $attempt->submitted_at?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $attempt->score ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $attempt->graded_at?->format('Y-m-d') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="4">No attempts yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
