<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Tambah Mata Kuliah') }}
            </h2>
            <x-button href="{{ route('admin.courses.index') }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
                Kembali
            </x-button>
        </div>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Info Mata Kuliah</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tambah mata kuliah baru</h3>
            </div>
        </x-slot>

        <form method="POST" action="{{ route('admin.courses.store') }}" class="space-y-6">
            @csrf

            <x-input label="Judul" name="title" type="text" icon="text-size" placeholder="Mata Kuliah title" required value="{{ old('title') }}" />
            <x-input label="Slug" name="slug" type="text" icon="link" placeholder="slug-mata-kuliah" required value="{{ old('slug') }}" />
            <x-textarea label="Deskripsi" name="description" rows="5" icon="notes" placeholder="Deskripsi (opsional)">{{ old('description') }}</x-textarea>

            <x-select label="Dosen" name="instructor_id" icon="user" placeholder="Pilih dosen" required>
                @foreach ($instructors as $instructor)
                    <option value="{{ $instructor->id }}" @selected(old('instructor_id') == $instructor->id)>
                        {{ $instructor->name }} ({{ $instructor->email }})
                    </option>
                @endforeach
            </x-select>

            <x-select label="Status" name="status" icon="toggle-right" placeholder="Pilih status" required>
                <option value="draft" @selected(old('status') === 'draft')>Draf</option>
                <option value="published" @selected(old('status') === 'published')>Terbit</option>
            </x-select>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <x-button type="submit" icon="device-floppy" class="w-full sm:w-auto">Simpan</x-button>
                <x-button href="{{ route('admin.courses.index') }}" variant="secondary" class="w-full sm:w-auto">Cancel</x-button>
            </div>
        </form>
    </x-card>
</x-app-layout>

