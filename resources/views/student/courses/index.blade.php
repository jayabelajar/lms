<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Mata Kuliah
            </h2>
        </div>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Mata Kuliah</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mata Kuliah Terdaftar</h3>
            </div>
        </x-slot>

        @php
            $gradients = [
                'from-indigo-600 to-violet-500',
                'from-emerald-500 to-teal-400',
                'from-amber-500 to-orange-400',
                'from-sky-500 to-blue-600',
                'from-rose-500 to-pink-500',
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
            @forelse ($courses as $course)
                @php
                    $hash = crc32((string) $course->id);
                    $gradient = $gradients[$hash % count($gradients)];
                    $statusLabel = $course->pivot->status ?? 'approved';
                    $statusText = $statusLabel === 'approved' ? 'Terdaftar' : ucfirst($statusLabel);
                @endphp
                <div class="rounded-3xl border border-gray-100 dark:border-gray-800 overflow-hidden bg-white dark:bg-gray-900 min-w-0">
                    <div class="w-full aspect-[16/9] bg-gradient-to-tr {{ $gradient }} relative">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute inset-x-0 bottom-0 p-4">
                            <div class="bg-black/35 backdrop-blur-sm rounded-2xl px-3 py-2">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-white/80">Mata Kuliah</span>
                                <h4 class="text-base sm:text-lg font-bold text-white leading-tight line-clamp-2">{{ $course->title }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 sm:p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">Dosen</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white text-right line-clamp-1">{{ $course->instructor?->name ?? '-' }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">Status</p>
                            <x-badge size="sm" variant="info">{{ $statusText }}</x-badge>
                        </div>
                        <x-button href="{{ route('student.my-courses.show', $course) }}" size="sm" variant="secondary" icon="eye" class="w-full justify-center">
                            Detail
                        </x-button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 py-8">Belum ada mata kuliah.</div>
            @endforelse
        </div>
    </x-card>

    <div>
        {{ $courses->links() }}
    </div>
</x-app-layout>
