<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Kuis
            </h2>
            <x-button href="{{ route('instructor.quizzes.create') }}" icon="plus" class="w-full sm:w-auto">
                Baru Kuis
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
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Kuis</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">All quizzes</h3>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Mata Kuliah</th>
                        <th class="px-4 py-3">Terbit</th>
                        <th class="px-4 py-3">Aksis</th>
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
                                    <x-button href="{{ route('instructor.quizzes.edit', $quiz) }}" size="sm" variant="secondary" icon="edit">
                                        Ubah
                                    </x-button>
                                    <x-button href="{{ route('instructor.quizzes.attempts', $quiz) }}" size="sm" variant="secondary" icon="list-check">
                                        Attempts
                                    </x-button>
                                    <form method="POST" action="{{ route('instructor.quizzes.destroy', $quiz) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" size="sm" variant="danger" icon="trash"
                                            onclick="return confirm('Hapus quiz?')">
                                            Hapus
                                        </x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="4">Belum ada kuis.</td>
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
