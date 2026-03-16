<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Add courses baru') }}
            </h2>
        </div>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add courses baru</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang add courses baru.</p>
                </div>
                <div class="flex sm:justify-end gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                    <x-button href="{{ route('admin.courses.index') }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
                        Back
                    </x-button>
                </div>
            </div>
        </x-slot>

        <form method="POST" action="{{ route('admin.courses.store') }}" class="space-y-6">
            @csrf

            <x-input label="Title" name="title" type="text" icon="text-size" placeholder="Courses title" required value="{{ old('title') }}" />
            <x-input label="Slug" name="slug" type="text" icon="link" placeholder="slug-mata-kuliah" required value="{{ old('slug') }}" />
            <x-textarea label="Description" name="description" rows="5" icon="notes" placeholder="Description (optional)">{{ old('description') }}</x-textarea>

            <x-select label="Instructors" name="instructor_id" icon="user" placeholder="Select instructors" required>
                @foreach ($instructors as $instructor)
                    <option value="{{ $instructor->id }}" @selected(old('instructor_id') == $instructor->id)>
                        {{ $instructor->name }} ({{ $instructor->email }})
                    </option>
                @endforeach
            </x-select>

            <x-select label="Status" name="status" icon="toggle-right" placeholder="Select status" required>
                <option value="draftt" @selected(old('status') === 'draftt')>Draft</option>
                <option value="published" @selected(old('status') === 'published')>Published</option>
            </x-select>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <x-button type="submit" icon="device-floppy" class="w-full sm:w-auto">Save</x-button>
                <x-button href="{{ route('admin.courses.index') }}" variant="secondary" class="w-full sm:w-auto">Cancel</x-button>
            </div>
        </form>
    </x-card>
</x-app-layout>

