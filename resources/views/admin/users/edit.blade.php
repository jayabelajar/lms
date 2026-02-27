<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Ubah Pengguna') }}
            </h2>
            <x-button href="{{ route('admin.users.index') }}" variant="secondary" icon="arrow-left" class="w-full sm:w-auto">
                Kembali
            </x-button>
        </div>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">User Info</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
            </div>
        </x-slot>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Name" name="name" type="text" icon="user" placeholder="Full name" required value="{{ old('name', $user->name) }}" />
                <x-input label="Email" name="email" type="email" icon="mail" placeholder="user@example.com" required value="{{ old('email', $user->email) }}" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Password (optional)" name="password" type="password" icon="lock" placeholder="********" />
                <x-select label="Role" name="role" icon="shield" placeholder="Select role" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @selected(old('role', $user->roles->first()?->name) == $role->name)>{{ $role->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="NIP (Admin/Dosen)" name="nip" type="text" icon="id" placeholder="NIP" value="{{ old('nip', $user->nip) }}" />
                <x-input label="NIM (Mahasiswa)" name="nim" type="text" icon="id" placeholder="NIM" value="{{ old('nim', $user->nim) }}" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-input label="Semester" name="semester" type="text" icon="layers" placeholder="Contoh: 1/2/Ganjil" value="{{ old('semester', $user->semester) }}" />
                <x-input label="Angkatan" name="angkatan" type="text" icon="calendar" placeholder="Contoh: 2023" value="{{ old('angkatan', $user->angkatan) }}" />
                <x-input label="Kelas" name="kelas" type="text" icon="building" placeholder="Contoh: TI-1A" value="{{ old('kelas', $user->kelas) }}" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Jurusan" name="jurusan" type="text" icon="building-community" placeholder="Contoh: Teknik Informatika" value="{{ old('jurusan', $user->jurusan) }}" />
                <x-input label="Prodi" name="prodi" type="text" icon="book-2" placeholder="Contoh: S1 Informatika" value="{{ old('prodi', $user->prodi) }}" />
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <x-button type="submit" icon="device-floppy" class="w-full sm:w-auto">Perbarui</x-button>
                <x-button href="{{ route('admin.users.index') }}" variant="secondary" class="w-full sm:w-auto">Cancel</x-button>
            </div>
        </form>
    </x-card>
</x-app-layout>
