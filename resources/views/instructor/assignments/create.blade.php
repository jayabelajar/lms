<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Baru Tugas - {{ $course->title }}
            </h2>
            <x-button href="{{ route('instructor.assignments.index', $course) }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
                Kembali
            </x-button>
        </div>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Tugas</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Buat tugas</h3>
            </div>
        </x-slot>

        <form method="POST" action="{{ route('instructor.assignments.store', $course) }}" class="space-y-6">
            @csrf
            <x-input label="Judul" name="title" type="text" icon="list" placeholder="Tugas title" required />
            <x-textarea label="Deskripsi" name="description" icon="notes" rows="5"></x-textarea>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Due Date" name="due_at" type="date" icon="calendar" />
                <x-input label="Skor Maks" name="max_score" type="number" icon="award" value="100" />
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <x-button type="submit" icon="device-floppy" class="w-full sm:w-auto">Simpan</x-button>
                <x-button href="{{ route('instructor.assignments.index', $course) }}" variant="secondary" class="w-full sm:w-auto">Cancel</x-button>
            </div>
        </form>
    </x-card>
</x-app-layout>
