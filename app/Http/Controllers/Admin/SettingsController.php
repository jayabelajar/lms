<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $settings = [
            'lms_name' => Setting::getValue('lms_name', config('app.name')),
            'academic_year' => Setting::getValue('academic_year'),
            'maintenance_mode' => Setting::getValue('maintenance_mode', 'off'),
            'logo_path' => Setting::getValue('logo_path'),
        ];

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'lms_name' => ['required', 'string', 'max:255'],
            'academic_year' => ['nullable', 'string', 'max:100'],
            'maintenance_mode' => ['nullable', 'in:on,off'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        Setting::setValue('lms_name', $data['lms_name']);
        Setting::setValue('academic_year', $data['academic_year'] ?? null);
        Setting::setValue('maintenance_mode', $data['maintenance_mode'] ?? 'off');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            Setting::setValue('logo_path', $path);
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Settings updated.');
    }
}
