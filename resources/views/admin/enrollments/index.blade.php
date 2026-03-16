<x-app-layout>

    @if (session('status'))
        <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl">
            {{ session('status') }}
        </div>
    @endif

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">All enrollments</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang all enrollments.</p>
                </div>
            </div>
        </x-slot>

        <form method="GET" action="{{ route('admin.enrollments.index') }}" class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-4 lg:col-span-5 w-full">
                    <x-input label="Search Student / Course" name="q" type="text" icon="search" placeholder="Search by name or title..." value="{{ request('q') }}" />
                </div>
                <div class="md:col-span-5 lg:col-span-4 w-full">
                    <x-select name="status" label="Filter Status" icon="toggle-left" placeholder="All Statuses" onchange="this.form.submit()">
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    </x-select>
                </div>
                <div class="md:col-span-3 lg:col-span-3 w-full flex items-center gap-2 sm:gap-3">
                    <x-button type="submit" icon="filter" class="flex-1 justify-center">Filter</x-button>
                    <x-button href="{{ route('admin.enrollments.index') }}" variant="secondary" class="flex-1 justify-center">Reset</x-button>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Students</th>
                        <th class="px-4 py-3">Courses</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
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
