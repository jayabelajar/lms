<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Ubah Kuis - {{ $quiz->title }}
            </h2>
            <x-button href="{{ route('instructor.quizzes.index') }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
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
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Kuis</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Details</h3>
            </div>
        </x-slot>
        <form method="POST" action="{{ route('instructor.quizzes.update', $quiz) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <x-input label="Judul" name="title" type="text" icon="list" value="{{ $quiz->title }}" required />
            <x-textarea label="Deskripsi" name="description" icon="notes" rows="4">{{ $quiz->description }}</x-textarea>
            <div class="flex items-center gap-3">
                <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                    <input type="checkbox" name="published" value="1" class="mr-2" @checked($quiz->published)> Publish
                </label>
            </div>
            <x-button type="submit" icon="device-floppy">Simpan</x-button>
        </form>
    </x-card>

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Questions</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">MCQ & Essay</h3>
            </div>
        </x-slot>

        <form method="POST" action="{{ route('instructor.quizzes.questions.store', $quiz) }}" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-select name="type" label="Type" icon="category" required>
                    <option value="mcq">Multiple Choice</option>
                    <option value="essay">Essay</option>
                </x-select>
                <x-input name="points" label="Points" type="number" icon="award" value="1" />
            </div>
            <x-textarea name="question" label="Question" icon="help" rows="3"></x-textarea>
            <x-input name="sort_order" label="Order" icon="arrows-sort" value="0" />
            <x-button type="submit" icon="plus">Tambah Question</x-button>
        </form>

        <div class="mt-6 space-y-4">
            @forelse ($quiz->questions as $question)
                <div class="p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400">{{ strtoupper($question->type) }}</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $question->question }}</p>
                        </div>
                        <form method="POST" action="{{ route('instructor.questions.destroy', $question) }}">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" size="sm" variant="danger" icon="trash">Hapus</x-button>
                        </form>
                    </div>

                    <form method="POST" action="{{ route('instructor.questions.update', $question) }}" class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                        @csrf
                        @method('PUT')
                        <input name="question" value="{{ $question->question }}" class="rounded-xl px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 md:col-span-2">
                        <input name="points" value="{{ $question->points }}" class="rounded-xl px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <input name="sort_order" value="{{ $question->sort_order }}" class="rounded-xl px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <x-button type="submit" size="sm" variant="secondary" icon="check">Perbarui</x-button>
                    </form>

                    @if ($question->type === 'mcq')
                        <div class="mt-4 space-y-3">
                            <form method="POST" action="{{ route('instructor.questions.options.store', $question) }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @csrf
                                <input name="option_text" placeholder="Option text" class="rounded-xl px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 md:col-span-2">
                                <label class="text-sm font-semibold text-gray-600 dark:text-gray-300 flex items-center gap-2">
                                    <input type="checkbox" name="is_correct" value="1"> Correct
                                </label>
                                <x-button type="submit" size="sm" icon="plus">Tambah Option</x-button>
                            </form>

                            @foreach ($question->options as $option)
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <form method="POST" action="{{ route('instructor.options.update', $option) }}" class="grid grid-cols-1 md:grid-cols-3 gap-3 md:col-span-3">
                                        @csrf
                                        @method('PUT')
                                        <input name="option_text" value="{{ $option->option_text }}" class="rounded-xl px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 md:col-span-2">
                                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-300 flex items-center gap-2">
                                            <input type="checkbox" name="is_correct" value="1" @checked($option->is_correct)> Correct
                                        </label>
                                        <x-button type="submit" size="sm" variant="secondary" icon="check">Simpan</x-button>
                                    </form>
                                    <form method="POST" action="{{ route('instructor.options.destroy', $option) }}" class="md:col-span-1">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" size="sm" variant="danger" icon="trash">Del</x-button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500">No questions yet.</p>
            @endforelse
        </div>
    </x-card>

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Attempts</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mahasiswa attempts</h3>
            </div>
        </x-slot>
        <x-button href="{{ route('instructor.quizzes.attempts', $quiz) }}" variant="secondary" icon="list-check">
            Lihat Attempts
        </x-button>
    </x-card>
</x-app-layout>
