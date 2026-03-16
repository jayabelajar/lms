<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Hasil Anda') }}
            </h2>
        </div>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hasil Anda</h3>
            
                    <x-button href="{{ route('student.quizzes.index') }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
                    Back
                </x-button>
                </div>
        </x-slot>

        <div class="flex flex-wrap gap-2">
            <x-badge size="sm" variant="success">Skor: {{ $attempt->score ?? 0 }}</x-badge>
            <x-badge size="sm" variant="gray">Submitted: {{ $attempt->submitted_at?->format('d M Y H:i') }}</x-badge>
        </div>
    </x-card>

    @php $answers = $attempt->answers->keyBy('quiz_question_id'); @endphp

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tinjau pertanyaan</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang tinjau pertanyaan.</p>
                </div>
                <div class="flex items-center gap-3">
                    
                </div>
            </div>
        </x-slot>

        <div class="space-y-6">
            @foreach ($quiz->questions as $index => $question)
                @php
                    $answer = $answers[$question->id] ?? null;
                    $correctOption = $question->type === 'mcq' ? $question->options->firstWhere('is_correct', true) : null;
                @endphp
                <div class="border border-gray-100 dark:border-gray-800 rounded-2xl p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Q{{ $index + 1 }}. {{ $question->question }}</p>
                            <p class="text-xs text-gray-400">{{ strtoupper($question->type) }}  {{ $question->points }} pts</p>
                        </div>
                        @if ($question->type === 'mcq')
                            <x-badge size="sm" variant="{{ $answer?->is_correct ? 'success' : 'danger' }}">
                                {{ $answer?->is_correct ? 'Correct' : 'Incorrect' }}
                            </x-badge>
                        @endif
                    </div>

                    <div class="mt-4 space-y-2 text-sm text-gray-700 dark:text-gray-200">
                        @if ($question->type === 'mcq')
                            <p>Your answer: <span class="font-semibold">{{ $question->options->firstWhere('id', $answer?->selected_option_id)?->option_text ?? '-' }}</span></p>
                            <p>Correct answer: <span class="font-semibold">{{ $correctOption?->option_text ?? '-' }}</span></p>
                        @else
                            <p>Your answer:</p>
                            <div class="rounded-2xl bg-gray-50 dark:bg-gray-800 p-4">
                                {{ $answer?->answer_text ?: '-' }}
                            </div>
                            @if ($answer && $answer->score !== null)
                                <p>Skor: <span class="font-semibold">{{ $answer->score }}</span></p>
                            @else
                                <x-badge size="sm" variant="warning">Menunggu grading</x-badge>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </x-card>
</x-app-layout>
