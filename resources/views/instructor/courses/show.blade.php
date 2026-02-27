<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ $course->title }}
            </h2>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <x-button href="{{ route('instructor.assignments.index', $course) }}" variant="secondary" icon="list-check" class="w-full sm:w-auto">
                    Tugas
                </x-button>
                <x-button href="{{ route('instructor.courses.edit', $course) }}" icon="edit" class="w-full sm:w-auto">
                    Ubah
                </x-button>
            </div>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <x-card class="xl:col-span-2">
            <x-slot name="header">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Mata Kuliah Ringkasan</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Details</h3>
                </div>
            </x-slot>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Status</p>
                        <span class="inline-flex mt-2 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                            {{ $course->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $course->status }}
                        </span>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Mahasiswa</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $course->students->count() }}</p>
                    </div>
                </div>
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-200">
                    {{ $course->description ?: 'No description.' }}
                </div>
            </div>
        </x-card>

        <x-card>
            <x-slot name="header">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Participants</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Enrolled Mahasiswa</h3>
                </div>
            </x-slot>
            <ul class="space-y-3">
                @forelse ($course->students as $student)
                    <li class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $student->name }}</p>
                            <p class="text-xs text-gray-500">{{ $student->email }}</p>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-gray-500">No enrollments yet.</li>
                @endforelse
            </ul>
        </x-card>
    </div>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Modules</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Sections & Materi</h3>
                </div>
            </div>
        </x-slot>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Tambah Section</h4>
                <form method="POST" action="{{ route('instructor.sections.store', $course) }}" class="space-y-3">
                    @csrf
                    <x-input name="title" label="Section Judul" icon="list" placeholder="Week 1: Intro" required />
                    <x-input name="sort_order" label="Order" icon="arrows-sort" placeholder="0" />
                    <x-button type="submit" icon="plus">Tambah Section</x-button>
                </form>
            </div>

            <div class="space-y-4">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Tambah Material</h4>
                <form method="POST" action="{{ route('instructor.materials.store', $course) }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <x-select name="course_section_id" label="Section (opsional)" icon="list">
                        <option value="">Tanpa section (Umum)</option>
                        @foreach ($course->sections as $section)
                            <option value="{{ $section->id }}">{{ $section->title }}</option>
                        @endforeach
                    </x-select>
                    <x-input name="title" label="Material Judul" icon="book" required />
                    <x-select name="type" label="Type" icon="category" required>
                        <option value="text">Text</option>
                        <option value="file">Berkas</option>
                        <option value="video">Video</option>
                    </x-select>
                    <x-textarea name="content" label="Text Content" icon="notes" rows="3"></x-textarea>
                    <x-input name="video_url" label="Video URL" icon="link" placeholder="https://..." />
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-gray-400 uppercase ml-1 tracking-widest">Unggah Berkas</label>
                        <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                    <x-input name="sort_order" label="Order" icon="arrows-sort" placeholder="0" />
                    <x-button type="submit" icon="plus">Tambah Material</x-button>
                </form>
            </div>
        </div>

        <div class="mt-8 space-y-6">
            @forelse ($course->sections as $section)
                <div class="p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Section</p>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $section->title }}</h4>
                        </div>
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                            <form method="POST" action="{{ route('instructor.sections.update', $section) }}" class="flex gap-2">
                                @csrf
                                @method('PUT')
                                <input name="title" value="{{ $section->title }}" class="rounded-xl px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                <input name="sort_order" value="{{ $section->sort_order }}" class="rounded-xl px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 w-20">
                                <x-button type="submit" size="sm" variant="secondary" icon="check">Simpan</x-button>
                            </form>
                            <form method="POST" action="{{ route('instructor.sections.destroy', $section) }}">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" size="sm" variant="danger" icon="trash">Hapus</x-button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($section->materials as $material)
                            <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800/60 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">{{ $material->type }}</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $material->title }}</p>
                                </div>
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <form method="POST" action="{{ route('instructor.materials.update', $material) }}" enctype="multipart/form-data" class="flex gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input name="title" value="{{ $material->title }}" class="rounded-xl px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                        <input name="sort_order" value="{{ $material->sort_order }}" class="rounded-xl px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 w-20">
                                        <x-button type="submit" size="sm" variant="secondary" icon="check">Simpan</x-button>
                                    </form>
                                    <form method="POST" action="{{ route('instructor.materials.destroy', $material) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" size="sm" variant="danger" icon="trash">Hapus</x-button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada materi di bagian ini.</p>
                        @endforelse
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada bagian.</p>
            @endforelse
        </div>
    </x-card>
</x-app-layout>
