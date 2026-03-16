<x-app-layout>
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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <x-card class="xl:col-span-2">
            <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Details</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang details.</p>
                </div>
                <div class="flex items-center gap-3">
                    
                </div>
            </div>
        </x-slot>

            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $assignment->description ?: 'No description.' }}
                </p>
                <div class="flex flex-wrap gap-2">
                    <x-badge size="sm" variant="gray">Due: {{ $assignment->due_at?->format('d M Y') ?? '-' }}</x-badge>
                    <x-badge size="sm" variant="gray">Skor Maks: {{ $assignment->max_score ?? '-' }}</x-badge>
                </div>
            </div>
        </x-card>

        <x-card>
            <x-slot name="header">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pengumpulan Anda</h3>
                
                    <x-button href="{{ route('student.assignments.index') }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
                    Back
                </x-button>
                </div>
            </x-slot>

            <div class="space-y-3">
                @if ($submission)
                    <x-badge size="sm" variant="{{ $submission->graded_at ? 'success' : 'info' }}">
                        {{ $submission->graded_at ? 'Digrades' : 'Submitted' }}
                    </x-badge>
                    <p class="text-sm text-gray-500">Dikumpulkan pada: {{ $submission->submitted_at?->format('d M Y H:i') }}</p>
                    @if ($submission->score !== null)
                        <p class="text-sm text-gray-600 dark:text-gray-300">Skor: <span class="font-semibold text-gray-900 dark:text-white">{{ $submission->score }}</span></p>
                    @endif
                    @if ($submission->feedback)
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            <p class="font-semibold text-gray-900 dark:text-white">Feedback</p>
                            <p>{{ $submission->feedback }}</p>
                        </div>
                    @endif
                    @if ($submission->file_path)
                        <x-button href="{{ asset('storage/' . $submission->file_path) }}" size="sm" variant="secondary" icon="download">
                            Unduh Pengumpulan
                        </x-button>
                    @endif
                @else
                    <x-badge size="sm" variant="warning">Belum dikumpulkan</x-badge>
                @endif
            </div>
        </x-card>
    </div>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Upload assignments Anda</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang upload assignments anda.</p>
                </div>
                <div class="flex items-center gap-3">
                    
                </div>
            </div>
        </x-slot>

        @php
            $isLate = $assignment->due_at && now()->gt($assignment->due_at->endOfDay());
        @endphp

        @if ($isLate)
            <div class="p-3 bg-amber-50 text-amber-700 border border-amber-100 rounded-2xl mb-4">
                Batas waktu telah lewat. Pengumpulan diclose.
            </div>
        @endif

        <form method="POST" action="{{ $submission ? route('student.assignments.update', $assignment) : route('student.assignments.submit', $assignment) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @if ($submission)
                @method('PUT')
            @endif

            <x-textarea name="content" label="Catatan" rows="5">{{ old('content', $submission?->content) }}</x-textarea>

            <div class="space-y-2">
                <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-300 uppercase tracking-widest">File</label>
                <input type="file" name="file" class="w-full text-sm file:mr-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-indigo-600 dark:file:bg-gray-800 dark:file:text-indigo-400" />
            </div>

            <x-button type="submit" icon="send" class="w-full sm:w-auto" @if($isLate) disabled @endif>
                {{ $submission ? 'Perbarui Pengumpulan' : 'Submit Assignments' }}
            </x-button>
        </form>
    </x-card>
</x-app-layout>
