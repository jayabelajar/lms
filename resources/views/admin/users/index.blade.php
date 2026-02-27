<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('User Management') }}
            </h2>
            <x-button href="{{ route('admin.users.create') }}" icon="plus" class="w-full sm:w-auto">
                Tambah Pengguna
            </x-button>
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
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Filters</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Find users</h3>
            </div>
        </x-slot>

        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-input label="Cari" name="q" type="text" icon="search" placeholder="Name, email, NIM, NIP" value="{{ request('q') }}" />

                <div class="w-full space-y-2">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase ml-1 tracking-widest">Role</label>
                    <select name="role" class="w-full rounded-2xl py-3 text-sm font-bold bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-white border border-gray-200 dark:border-gray-700">
                        <option value="">All</option>
                        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                        <option value="instructor" @selected(request('role') === 'instructor')>Dosen</option>
                        <option value="student" @selected(request('role') === 'student')>Mahasiswa</option>
                    </select>
                </div>

                <div class="w-full space-y-2">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase ml-1 tracking-widest">Status</label>
                    <select name="status" class="w-full rounded-2xl py-3 text-sm font-bold bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-white border border-gray-200 dark:border-gray-700">
                        <option value="">All</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                    </select>
                </div>

                <div class="w-full space-y-2">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase ml-1 tracking-widest">Semester</label>
                    <select name="semester" class="w-full rounded-2xl py-3 text-sm font-bold bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-white border border-gray-200 dark:border-gray-700">
                        <option value="">All</option>
                        @foreach (array_filter($users->pluck('semester')->unique()->toArray()) as $s)
                            <option value="{{ $s }}" @selected(request('semester') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-input label="Angkatan" name="angkatan" type="text" icon="calendar" placeholder="2023" value="{{ request('angkatan') }}" />
                <x-input label="Kelas" name="kelas" type="text" icon="building" placeholder="TI-1A" value="{{ request('kelas') }}" />
                <x-input label="Jurusan" name="jurusan" type="text" icon="building-community" placeholder="Teknik Informatika" value="{{ request('jurusan') }}" />
                <x-input label="Prodi" name="prodi" type="text" icon="book-2" placeholder="S1 Informatika" value="{{ request('prodi') }}" />
                <div class="flex items-end gap-3">
                    <x-button type="submit" icon="filter" class="w-full sm:w-auto">Apply</x-button>
                    <x-button href="{{ route('admin.users.index') }}" variant="secondary" class="w-full sm:w-auto">Reset</x-button>
                </div>
            </div>
        </form>
    </x-card>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Pengguna</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">All users</h3>
                </div>
                <span class="text-xs font-bold text-gray-400">{{ $users->total() }} total</span>
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
                        <th class="px-4 py-3">Aksis</th>
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
                                    <x-button href="{{ route('admin.users.edit', $user) }}" size="sm" variant="secondary" icon="edit">
                                        Ubah
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
                                            onclick="return confirm('Hapus user?')">
                                            Hapus
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
