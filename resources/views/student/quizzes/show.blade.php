<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ $quiz->title }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Mata Kuliah: {{ $quiz->course?->title ?? '-' }}</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <x-button href="{{ route('student.quizzes.index') }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
                    Kembali
                </x-button>
            </div>
        </div>
    </x-slot>

    @if ($attempt)
        <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl">
            You already submitted this quiz. You can review your answers.
            <a class="underline font-semibold" href="{{ route('student.quizzes.review', $quiz) }}">Tinjau</a>
        </div>
    @endif

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Kuis</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jawab pertanyaan</h3>
            </div>
        </x-slot>

        <form method="POST" action="{{ route('student.quizzes.submit', $quiz) }}" class="space-y-6">
            @csrf

            @foreach ($quiz->questions as $index => $question)
                <div class="border border-gray-100 dark:border-gray-800 rounded-2xl p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Q{{ $index + 1 }}. {{ $question->question }}</p>
                            <p class="text-xs text-gray-400">{{ strtoupper($question->type) }}  {{ $question->points }} pts</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        @if ($question->type === 'mcq')
                            <div class="space-y-2">
                                @foreach ($question->options as $option)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                                        <input type="radio" name="mcq[{{ $question->id }}]" value="{{ $option->id }}" class="text-indigo-600" @if($attempt) disabled @endif>
                                        <span>{{ $option->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <textarea name="essay[{{ $question->id }}]" rows="4" class="w-full rounded-2xl py-3 px-4 text-sm font-semibold bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-white border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-indigo-500/30" @if($attempt) disabled @endif></textarea>
                        @endif
                    </div>
                </div>
            @endforeach

            @if (! $attempt)
                <x-button type="submit" icon="send" class="w-full sm:w-auto">Kirim Kuis</x-button>
            @endif
        </form>
    </x-card>
</x-app-layout>
