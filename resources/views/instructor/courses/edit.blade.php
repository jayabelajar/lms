<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ubah Mata Kuliah') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('instructor.courses.update', $course) }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Judul</label>
                    <input name="title" value="{{ old('title', $course->title) }}" class="mt-1 w-full border-gray-300 rounded" required>
                    @error('title') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="description" rows="4" class="mt-1 w-full border-gray-300 rounded">{{ old('description', $course->description) }}</textarea>
                    @error('description') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 w-full border-gray-300 rounded" required>
                        <option value="draft" @selected(old('status', $course->status) === 'draft')>Draf</option>
                        <option value="published" @selected(old('status', $course->status) === 'published')>Terbit</option>
                    </select>
                    @error('status') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Perbarui</button>
                    <a href="{{ route('instructor.courses.show', $course) }}" class="px-4 py-2 border rounded">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
