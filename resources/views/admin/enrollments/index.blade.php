<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Enrolmen Management') }}
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
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Enrolmen</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">All enrollments</h3>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Mahasiswa</th>
                        <th class="px-4 py-3">Mata Kuliah</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($enrollments as $enrollment)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-900/40">
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $enrollment->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $enrollment->course?->title ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                                    {{ $enrollment->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $enrollment->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <form action="{{ route('admin.enrollments.update', $enrollment) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <x-button type="submit" size="sm" variant="success" icon="check">
                                            Approve
                                        </x-button>
                                    </form>
                                    <form action="{{ route('admin.enrollments.update', $enrollment) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <x-button type="submit" size="sm" variant="danger" icon="x">
                                            Reject
                                        </x-button>
                                    </form>
                                    <form action="{{ route('admin.enrollments.destroy', $enrollment) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" size="sm" variant="ghost" icon="trash"
                                            onclick="return confirm('Remove student from course?')">
                                            Remove
                                        </x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="4">No enrollments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div>
        {{ $enrollments->links() }}
    </div>
</x-app-layout>
