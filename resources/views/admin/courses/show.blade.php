<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ $course->title }}
            </h2>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <x-button href="{{ route('admin.courses.edit', $course) }}" icon="edit" class="w-full sm:w-auto">Ubah</x-button>
                <x-button href="{{ route('admin.courses.index') }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">Kembali</x-button>
            </div>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <x-card class="lg:col-span-2">
            <x-slot name="header">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Detail Mata Kuliah</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ringkasan</h3>
                </div>
            </x-slot>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Slug</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $course->slug }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Dosen</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $course->instructor?->name ?? '-' }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Status</p>
                    <span class="inline-flex mt-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                        {{ $course->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $course->status }}
                    </span>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Deskripsi</p>
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-200">
                        {{ $course->description ?: 'Belum ada deskripsi.' }}
                    </div>
                </div>
            </div>
        </x-card>

        <x-card>
            <x-slot name="header">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Peserta</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mahasiswa Terdaftar</h3>
                </div>
            </x-slot>

            <form method="POST" action="{{ route('admin.courses.students.add', $course) }}" class="space-y-3 mb-4">
                @csrf
                <x-select name="student_id" label="Tambah Mahasiswa" icon="users" required>
                    <option value="">Pilih mahasiswa</option>
                    @foreach ($availableStudents as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                    @endforeach
                </x-select>
                <x-button type="submit" icon="plus" class="w-full">Tambahkan</x-button>
            </form>

            <ul class="space-y-3">
                @forelse ($course->students as $student)
                    <li class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $student->name }}</p>
                            <p class="text-xs text-gray-500">{{ $student->email }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-600">Aktif</span>
                            <form method="POST" action="{{ route('admin.courses.students.remove', [$course, $student]) }}">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" size="sm" variant="danger" icon="trash"
                                    onclick="return confirm('Hapus mahasiswa dari mata kuliah?')">
                                    Hapus
                                </x-button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-gray-500">Belum ada mahasiswa.</li>
                @endforelse
            </ul>
        </x-card>
    </div>
</x-app-layout>
