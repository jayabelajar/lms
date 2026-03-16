<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Buat quizzes') }}
            </h2>
        </div>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Buat quizzes</h3>
            
                    <x-button href="{{ route('instructor.quizzes.index') }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
                Back
            </x-button>
                </div>
        </x-slot>

        <form method="POST" action="{{ route('instructor.quizzes.store') }}" class="space-y-6">
            @csrf
            <x-select name="course_id" label="Courses" icon="book" required>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </x-select>
            <x-input label="Title" name="title" type="text" icon="list" required />
            <x-textarea label="Description" name="description" icon="notes" rows="4"></x-textarea>
            <div class="flex items-center gap-3">
                <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                    <input type="checkbox" name="published" value="1" class="mr-2"> Publish
                </label>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <x-button type="submit" icon="device-floppy" class="w-full sm:w-auto">Save</x-button>
                <x-button href="{{ route('instructor.quizzes.index') }}" variant="secondary" class="w-full sm:w-auto">Cancel</x-button>
            </div>
        </form>
    </x-card>
</x-app-layout>
