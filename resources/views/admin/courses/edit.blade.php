<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('{{ $course->title }}') }}
            </h2>
        </div>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $course->title }}</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang {{ $course->title }}.</p>
                </div>
                <div class="flex sm:justify-end gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                    <x-button href="{{ route('admin.courses.show', $course) }}" data-drawer="true" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
                        Back
                    </x-button>
                </div>
            </div>
        </x-slot>

        <form method="POST" action="{{ route('admin.courses.update', $course) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <x-input label="Title" name="title" type="text" icon="text-size" placeholder="Courses title" required value="{{ old('title', $course->title) }}" />
            <x-input label="Slug" name="slug" type="text" icon="link" placeholder="slug-mata-kuliah" required value="{{ old('slug', $course->slug) }}" />
            <x-textarea label="Description" name="description" rows="5" icon="notes" placeholder="Description (optional)">{{ old('description', $course->description) }}</x-textarea>

            <x-select label="Instructors" name="instructor_id" icon="user" placeholder="Select instructors" required>
                @foreach ($instructors as $instructor)
                    <option value="{{ $instructor->id }}" @selected(old('instructor_id', $course->instructor_id) == $instructor->id)>
                        {{ $instructor->name }} ({{ $instructor->email }})
                    </option>
                @endforeach
            </x-select>

            <x-select label="Status" name="status" icon="toggle-right" placeholder="Select status" required>
                <option value="draftt" @selected(old('status', $course->status) === 'draftt')>Draft</option>
                <option value="published" @selected(old('status', $course->status) === 'published')>Published</option>
            </x-select>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <x-button type="submit" icon="device-floppy" class="w-full sm:w-auto">Perbarui</x-button>
                <x-button href="{{ route('admin.courses.show', $course) }}" data-drawer="true" variant="secondary" class="w-full sm:w-auto">Cancel</x-button>
            </div>
        </form>
    </x-card>
</x-app-layout>
