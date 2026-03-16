<x-app-layout>
    <div x-data="{
        showQuestionDrawer: false,
        questionMode: 'create', // create | edit
        questionAction: '{{ route('instructor.quizzes.questions.store', $quiz) }}',
        questionMethod: 'POST',
        questionType: 'mcq',
        questionPoints: '1',
        questionText: '',
        questionOrder: '0',
        
        showOptionDrawer: false,
        optionMode: 'create', // create | edit
        optionAction: '',
        optionMethod: 'POST',
        optionText: '',
        optionCorrect: false,

        createQuestion() {
            this.questionMode = 'create';
            this.questionAction = '{{ route('instructor.quizzes.questions.store', $quiz) }}';
            this.questionType = 'mcq';
            this.questionPoints = '1';
            this.questionText = '';
            this.questionOrder = '0';
            this.showQuestionDrawer = true;
        },
        editQuestion(id, type, points, text, order) {
            this.questionMode = 'edit';
            this.questionAction = '/dosen/questions/' + id;
            this.questionType = type;
            this.questionPoints = points;
            this.questionText = text;
            this.questionOrder = order;
            this.showQuestionDrawer = true;
        },
        createOption(questionId) {
            this.optionMode = 'create';
            this.optionAction = '/dosen/questions/' + questionId + '/options';
            this.optionText = '';
            this.optionCorrect = false;
            this.showOptionDrawer = true;
        },
        editOption(id, text, correct) {
            this.optionMode = 'edit';
            this.optionAction = '/dosen/options/' + id;
            this.optionText = text;
            this.optionCorrect = correct;
            this.showOptionDrawer = true;
        }
    }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Details') }}
        </h2>
    </x-slot>

    @if (session('status'))
        <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl">
            {{ session('status') }}
        </div>
    @endif

    <x-card>
        <x-slot name="header">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Details</h3>
            
                    <x-button href="{{ route('instructor.quizzes.index') }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
                Back
            </x-button>
                </div>
        </x-slot>
        <form method="POST" action="{{ route('instructor.quizzes.update', $quiz) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <x-input label="Title" name="title" type="text" icon="list" value="{{ $quiz->title }}" required />
            <x-textarea label="Description" name="description" icon="notes" rows="4">{{ $quiz->description }}</x-textarea>
            <div class="flex items-center gap-3">
                <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                    <input type="checkbox" name="published" value="1" class="mr-2" @checked($quiz->published)> Publish
                </label>
            </div>
            <x-button type="submit" icon="device-floppy">Save</x-button>
        </form>
    </x-card>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">MCQ & Essay</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang mcq & essay.</p>
                </div>
                <div class="flex sm:justify-end gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                    <x-button type="button" @click="createQuestion()" icon="plus" class="w-full sm:w-auto">Add Question</x-button>
                </div>
            </div>
        </x-slot>

        <div class="mt-6 space-y-4">
            @forelse ($quiz->questions as $question)
                <div class="p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $question->question }}</p>
                        </div>
                        <form method="POST" action="{{ route('instructor.questions.destroy', $question) }}">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" size="sm" variant="danger" icon="trash">Delete</x-button>
                        </form>
                    </div>

                        <div class="flex items-center gap-2 mt-4">
                            <x-button type="button" @click="editQuestion('{{ $question->id }}', '{{ $question->type }}', '{{ $question->points }}', '{{ escapeshellcmd($question->question) }}', '{{ $question->sort_order }}')" size="sm" variant="secondary" icon="edit">Edit</x-button>
                        </div>

                    @if ($question->type === 'mcq')
                        <div class="mt-4 space-y-3">
                            <div class="flex sm:justify-end">
                                <x-button type="button" size="sm" @click="createOption('{{ $question->id }}')" icon="plus">Add Option</x-button>
                            </div>

                            @foreach ($question->options as $option)
                                <div class="p-3 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center gap-3">
                                        @if($option->is_correct)
                                            <span class="w-4 h-4 rounded-full bg-emerald-500 flex items-center justify-center text-white"><i class="ti ti-check text-xs"></i></span>
                                        @else
                                            <span class="w-4 h-4 rounded-full border border-gray-300 dark:border-gray-600"></span>
                                        @endif
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $option->option_text }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-button type="button" @click="editOption('{{ $option->id }}', '{{ escapeshellcmd($option->option_text) }}', {{ $option->is_correct ? 'true' : 'false' }})" size="sm" variant="secondary" icon="edit">Edit</x-button>
                                        <form method="POST" action="{{ route('instructor.options.destroy', $option) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" size="sm" variant="danger" icon="trash">Del</x-button>
                                        </form>
                                    </div>
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
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Students attempts</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang students attempts.</p>
                </div>
                <div class="flex items-center gap-3">
                    
                </div>
            </div>
        </x-slot>
        <x-button href="{{ route('instructor.quizzes.attempts', $quiz) }}" variant="secondary" icon="list-check">
            View Attempts
        </x-button>
    </x-card>

    <!-- Question Drawer -->
    <div x-show="showQuestionDrawer" style="display: none;" class="relative z-[100]">
        <div x-transition.opacity class="fixed inset-0 bg-gray-900/80" @click="showQuestionDrawer = false"></div>
        <div class="fixed inset-y-0 right-0 flex w-full sm:w-[500px] pointer-events-none">
            <div x-transition.transform="" class="w-full h-full flex flex-col bg-white dark:bg-gray-900 shadow-2xl pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="questionMode === 'create' ? 'Add Question' : 'Edit Question'"></h3>
                    <button type="button" @click="showQuestionDrawer = false" class="text-gray-400 hover:text-gray-500">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto w-full flex-1">
                    <form :action="questionAction" method="POST" class="space-y-4">
                        @csrf
                        <template x-if="questionMode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <x-select x-model="questionType" name="type" label="Type" icon="category" required>
                            <option value="mcq">Multiple Choice</option>
                            <option value="essay">Essay</option>
                        </x-select>
                        <x-input x-model="questionPoints" name="points" label="Points" type="number" icon="award" />
                        <x-textarea x-model="questionText" name="question" label="Question" icon="help" rows="4"></x-textarea>
                        <x-input x-model="questionOrder" name="sort_order" label="Order" type="number" icon="arrows-sort" />
                        <x-button type="submit" icon="check" class="w-full">Save Question</x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Option Drawer -->
    <div x-show="showOptionDrawer" style="display: none;" class="relative z-[100]">
        <div x-transition.opacity class="fixed inset-0 bg-gray-900/80" @click="showOptionDrawer = false"></div>
        <div class="fixed inset-y-0 right-0 flex w-full sm:w-[500px] pointer-events-none">
            <div x-transition.transform="" class="w-full h-full flex flex-col bg-white dark:bg-gray-900 shadow-2xl pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="optionMode === 'create' ? 'Add Option' : 'Edit Option'"></h3>
                    <button type="button" @click="showOptionDrawer = false" class="text-gray-400 hover:text-gray-500">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto w-full flex-1">
                    <form :action="optionAction" method="POST" class="space-y-4">
                        @csrf
                        <template x-if="optionMode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <x-textarea x-model="optionText" name="option_text" label="Option text" icon="list" rows="3"></x-textarea>
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-300 flex items-center gap-2">
                            <input type="checkbox" name="is_correct" value="1" x-model="optionCorrect"> Correct Answer
                        </label>
                        <x-button type="submit" icon="check" class="w-full">Save Option</x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    </div>
</x-app-layout>
