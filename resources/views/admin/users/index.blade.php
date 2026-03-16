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
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Find users</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang find users.</p>
                </div>
            </div>
        </x-slot>

        <form method="GET" action="{{ route('admin.users.index') }}" class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-end">
                <div class="lg:col-span-3 w-full">
                    <x-input label="Search User" name="q" type="text" icon="search" placeholder="Name, email, NIM, NIP" value="{{ request('q') }}" />
                </div>

                <div class="lg:col-span-3 w-full">
                    <x-select name="role" label="Filter Role" icon="users" placeholder="All Roles" onchange="this.form.submit()">
                        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                        <option value="instructor" @selected(request('role') === 'instructor')>Instructors</option>
                        <option value="student" @selected(request('role') === 'student')>Students</option>
                    </x-select>
                </div>

                <div class="lg:col-span-3 w-full">
                    <x-select name="status" label="Filter Status" icon="toggle-left" placeholder="All Statuses" onchange="this.form.submit()">
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                    </x-select>
                </div>

                <div class="lg:col-span-3 w-full">
                    <x-select name="semester" label="Filter Semester" icon="school" placeholder="All Semesters" onchange="this.form.submit()">
                        @foreach (array_filter($users->pluck('semester')->unique()->toArray()) as $s)
                            <option value="{{ $s }}" @selected(request('semester') === $s)>{{ $s }}</option>
                        @endforeach
                    </x-select>
                </div>
                
                <div class="lg:col-span-2 w-full">
                    <x-input label="Angkatan" name="angkatan" type="text" icon="calendar" placeholder="2023" value="{{ request('angkatan') }}" />
                </div>
                <div class="lg:col-span-2 w-full">
                    <x-input label="Kelas" name="kelas" type="text" icon="building" placeholder="TI-1A" value="{{ request('kelas') }}" />
                </div>
                <div class="lg:col-span-3 w-full">
                    <x-input label="Jurusan" name="jurusan" type="text" icon="building-community" placeholder="Teknik Informatika" value="{{ request('jurusan') }}" />
                </div>
                <div class="lg:col-span-3 w-full">
                    <x-input label="Prodi" name="prodi" type="text" icon="book-2" placeholder="S1 Informatika" value="{{ request('prodi') }}" />
                </div>
                <div class="w-full lg:col-span-2 flex items-center gap-2 sm:gap-3">
                    <x-button type="submit" icon="filter" class="flex-1 justify-center px-1">Filter</x-button>
                    <x-button href="{{ route('admin.users.index') }}" variant="secondary" class="flex-1 justify-center px-1">Reset</x-button>
                </div>
            </div>
        </form>
    </x-card>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">All users</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang all users.</p>
                </div>
                <div class="flex sm:justify-end gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                    <x-button href="{{ route('admin.users.create') }}" data-drawer="true" icon="plus" class="w-full sm:w-auto">
                        Add Users
                    </x-button>
                </div>
            </div>
        </x-slot>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">NIM/NIP</th>
                        <th class="px-4 py-3">Semester</th>
                        <th class="px-4 py-3">Angkatan</th>
                        <th class="px-4 py-3">Kelas</th>
                        <th class="px-4 py-3">Jurusan</th>
                        <th class="px-4 py-3">Prodi</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-900/40">
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-indigo-100 text-indigo-700">
                                    {{ $user->roles->first()?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->nim ?? $user->nip ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->semester ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->angkatan ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->kelas ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->jurusan ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->prodi ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if ($user->isSuspended())
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-rose-100 text-rose-700">Suspended</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-emerald-100 text-emerald-700">Active</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <x-button href="{{ route('admin.users.edit', $user) }}" data-drawer="true" size="sm" variant="secondary" icon="edit">
                                        Edit
                                    </x-button>
                                    <form action="{{ route('admin.users.toggle-suspend', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <x-button type="submit" size="sm" variant="ghost" icon="ban">
                                            {{ $user->isSuspended() ? 'Unsuspend' : 'Suspend' }}
                                        </x-button>
                                    </form>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" size="sm" variant="danger" icon="trash"
                                            onclick="return confirm('Delete user?')">
                                            Delete
                                        </x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="11">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div>
        {{ $users->links() }}
    </div>
</x-app-layout>
