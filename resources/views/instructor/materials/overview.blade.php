<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Materi
            </h2>
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
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Tambah Cepat</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tambah materi langsung</h3>
            </div>
        </x-slot>
        <form method="POST" action="{{ route('instructor.materials.quick-store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-select name="course_id" label="Mata Kuliah" icon="book" required>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                </x-select>
                <x-select name="course_section_id" label="Section (opsional)" icon="list">
                    <option value="">Tanpa section (Umum)</option>
                    @foreach ($courses as $course)
                        @foreach ($course->sections as $section)
                            <option value="{{ $section->id }}">{{ $course->title }} - {{ $section->title }}</option>
                        @endforeach
                    @endforeach
                </x-select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input name="title" label="Material Judul" icon="book" required />
                <x-select name="type" label="Tipe" icon="category" required>
                    <option value="text">Text</option>
                    <option value="file">Berkas</option>
                    <option value="video">Video</option>
                </x-select>
            </div>
            <x-textarea name="content" label="Konten Teks" icon="notes" rows="3"></x-textarea>
            <x-input name="video_url" label="Video URL" icon="link" placeholder="https://..." />
            <div class="space-y-2">
                <label class="block text-[11px] font-bold text-gray-400 uppercase ml-1 tracking-widest">Unggah Berkas</label>
                <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
            <x-input name="sort_order" label="Order" icon="arrows-sort" placeholder="0" />
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <x-button type="submit" icon="plus" class="w-full sm:w-auto">Tambah Material</x-button>
            </div>
        </form>
    </x-card>

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Materi</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Semua mata kuliah</h3>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Mata Kuliah</th>
                        <th class="px-4 py-3">Section</th>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($materials as $material)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $material->course?->title ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $material->section?->title ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $material->title }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-indigo-100 text-indigo-700">
                                    {{ $material->type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $material->sort_order }}</td>
                            <td class="px-4 py-3">
                                <x-button href="{{ route('instructor.courses.show', $material->course) }}" size="sm" variant="secondary" icon="settings">
                                    Manage
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="6">Belum ada materi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div>
        {{ $materials->links() }}
    </div>
</x-app-layout>


