<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('System Pengaturan') }}
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
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">General</p>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">LMS Configuration</h3>
            </div>
        </x-slot>

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <x-input label="LMS Name" name="lms_name" type="text" icon="school" required value="{{ old('lms_name', $settings['lms_name']) }}" />
            <x-input label="Academic Year" name="academic_year" type="text" icon="calendar" value="{{ old('academic_year', $settings['academic_year']) }}" />

            <div class="space-y-2">
                <label class="block text-[11px] font-bold text-gray-400 dark:text-gray-300 uppercase ml-1 tracking-widest">Logo</label>
                <input type="file" name="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @if (!empty($settings['logo_path']))
                    <p class="text-xs text-gray-500">Current: {{ $settings['logo_path'] }}</p>
                @endif
            </div>

            <x-select label="Maintenance Mode" name="maintenance_mode" icon="tool" placeholder="Select mode">
                <option value="off" @selected(old('maintenance_mode', $settings['maintenance_mode']) === 'off')>Off</option>
                <option value="on" @selected(old('maintenance_mode', $settings['maintenance_mode']) === 'on')>On</option>
            </x-select>

            <div class="flex items-center gap-3">
                <x-button type="submit" icon="device-floppy">Simpan Pengaturan</x-button>
            </div>
        </form>
    </x-card>
</x-app-layout>
