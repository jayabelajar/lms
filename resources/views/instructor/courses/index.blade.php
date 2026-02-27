<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Mata Kuliah') }}
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
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Mata Kuliah</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">My courses</h3>
                </div>
                <span class="text-xs font-bold text-gray-400">{{ $courses->total() }} total</span>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($courses as $course)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-900/40">
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $course->title }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                                    {{ $course->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $course->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <x-button href="{{ route('instructor.courses.show', $course) }}" size="sm" variant="secondary" icon="eye">
                                        Lihat
                                    </x-button>
                                    <x-button href="{{ route('instructor.courses.edit', $course) }}" size="sm" variant="secondary" icon="edit">
                                        Ubah
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="3">Belum ada mata kuliah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div>
        {{ $courses->links() }}
    </div>
</x-app-layout>
