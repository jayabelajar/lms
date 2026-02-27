<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ $course->title }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Dosen: {{ $course->instructor?->name ?? '-' }}</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <x-button href="{{ route('student.assignments.index') }}" variant="secondary" icon="list-check" class="w-full sm:w-auto">
                    Tugas
                </x-button>
                <x-button href="{{ route('student.quizzes.index') }}" variant="secondary" icon="help" class="w-full sm:w-auto">
                    Kuis
                </x-button>
                <x-button href="{{ route('student.progress.index') }}" variant="secondary" icon="chart-bar" class="w-full sm:w-auto">
                    Progres
                </x-button>
                <x-button href="{{ route('student.my-courses') }}" icon="arrow-left" class="w-full sm:w-auto">
                    Kembali
                </x-button>
            </div>
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
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Ringkasan</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mata Kuliah details</h3>
            </div>
        </x-slot>
        <div class="space-y-3">
            <div class="flex flex-wrap gap-2">
                <x-badge variant="primary" size="sm">{{ ucfirst($course->status) }}</x-badge>
                <x-badge variant="gray" size="sm">{{ $course->materials()->count() }} Materi</x-badge>
                <x-badge variant="gray" size="sm">{{ $course->assignments()->count() }} Tugas</x-badge>
                <x-badge variant="gray" size="sm">{{ $course->quizzes()->count() }} Kuis</x-badge>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                {{ $course->description ?: 'Belum ada deskripsi.' }}
            </p>
        </div>
    </x-card>

    @php
        $completedIds = $completedIds ?? [];
    @endphp

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Mata Kuliah Content</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Materi and sections</h3>
            </div>
        </x-slot>

        <div class="space-y-6">
            @forelse ($course->sections as $section)
                <div class="border border-gray-100 dark:border-gray-800 rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $section->title }}</h4>
                        <span class="text-xs text-gray-400">{{ $section->materials->count() }} materials</span>
                    </div>

                    <div class="mt-4 space-y-4">
                        @forelse ($section->materials as $material)
                            @php $isCompleted = in_array($material->id, $completedIds, true); @endphp
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between bg-gray-50 dark:bg-gray-800/60 rounded-2xl p-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $material->title }}</p>
                                        <x-badge size="sm" variant="info">{{ strtoupper($material->type) }}</x-badge>
                                        @if ($isCompleted)
                                            <x-badge size="sm" variant="success">Completed</x-badge>
                                        @endif
                                    </div>
                                    @if ($material->type === 'text')
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ \Illuminate\Support\Str::limit($material->content ?? '', 200) }}
                                        </p>
                                    @elseif ($material->type === 'file')
                                        <p class="text-sm text-gray-600 dark:text-gray-300">Berkas material</p>
                                    @elseif ($material->type === 'video')
                                        <p class="text-sm text-gray-600 dark:text-gray-300">Video material</p>
                                    @endif
                                </div>

                                <div class="flex flex-col gap-2 sm:items-end">
                                    @if ($material->type === 'file' && $material->file_path)
                                        <x-button href="{{ asset('storage/' . $material->file_path) }}" size="sm" variant="secondary" icon="download">
                                            Unduh
                                        </x-button>
                                    @endif
                                    @if ($material->type === 'video' && $material->video_url)
                                        <x-button href="{{ $material->video_url }}" size="sm" variant="secondary" icon="player-play" target="_blank">
                                            Watch
                                        </x-button>
                                    @endif
                                    @if (! $isCompleted)
                                        <form method="POST" action="{{ route('student.materials.complete', $material) }}">
                                            @csrf
                                            <x-button type="submit" size="sm" icon="check">
                                                Mark Completed
                                            </x-button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">Belum ada materi di bagian ini.</div>
                        @endforelse
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada bagian.</p>
            @endforelse

            @php
                $unsectioned = $course->materials->whereNull('course_section_id');
            @endphp

            @if ($unsectioned->count() > 0)
                <div class="border border-gray-100 dark:border-gray-800 rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <h4 class="font-semibold text-gray-900 dark:text-white">Other Materi</h4>
                        <span class="text-xs text-gray-400">{{ $unsectioned->count() }} materials</span>
                    </div>

                    <div class="mt-4 space-y-4">
                        @foreach ($unsectioned as $material)
                            @php $isCompleted = in_array($material->id, $completedIds, true); @endphp
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between bg-gray-50 dark:bg-gray-800/60 rounded-2xl p-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $material->title }}</p>
                                        <x-badge size="sm" variant="info">{{ strtoupper($material->type) }}</x-badge>
                                        @if ($isCompleted)
                                            <x-badge size="sm" variant="success">Completed</x-badge>
                                        @endif
                                    </div>
                                    @if ($material->type === 'text')
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ \Illuminate\Support\Str::limit($material->content ?? '', 200) }}
                                        </p>
                                    @elseif ($material->type === 'file')
                                        <p class="text-sm text-gray-600 dark:text-gray-300">Berkas material</p>
                                    @elseif ($material->type === 'video')
                                        <p class="text-sm text-gray-600 dark:text-gray-300">Video material</p>
                                    @endif
                                </div>

                                <div class="flex flex-col gap-2 sm:items-end">
                                    @if ($material->type === 'file' && $material->file_path)
                                        <x-button href="{{ asset('storage/' . $material->file_path) }}" size="sm" variant="secondary" icon="download">
                                            Unduh
                                        </x-button>
                                    @endif
                                    @if ($material->type === 'video' && $material->video_url)
                                        <x-button href="{{ $material->video_url }}" size="sm" variant="secondary" icon="player-play" target="_blank">
                                            Watch
                                        </x-button>
                                    @endif
                                    @if (! $isCompleted)
                                        <form method="POST" action="{{ route('student.materials.complete', $material) }}">
                                            @csrf
                                            <x-button type="submit" size="sm" icon="check">
                                                Mark Completed
                                            </x-button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-card>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <x-card>
            <x-slot name="header">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Tugas</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mata Kuliah tasks</h3>
                </div>
            </x-slot>

            <div class="overflow-x-auto w-full">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Due</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($course->assignments as $assignment)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $assignment->title }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $assignment->due_at?->format('d M Y') ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <x-button href="{{ route('student.assignments.show', $assignment) }}" size="sm" variant="secondary" icon="arrow-right">
                                        Buka
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-gray-500" colspan="3">Belum ada tugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-card>
            <x-slot name="header">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Kuis</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Kuis list</h3>
                </div>
            </x-slot>

            <div class="overflow-x-auto w-full">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Kuis</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($course->quizzes as $quiz)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $quiz->title }}</td>
                                <td class="px-4 py-3">
                                    <x-badge size="sm" variant="{{ $quiz->published ? 'success' : 'warning' }}">
                                        {{ $quiz->published ? 'Terbit' : 'Draf' }}
                                    </x-badge>
                                </td>
                                <td class="px-4 py-3">
                                    <x-button href="{{ route('student.quizzes.show', $quiz) }}" size="sm" variant="secondary" icon="arrow-right">
                                        Buka
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-gray-500" colspan="3">Belum ada kuis.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-app-layout>
