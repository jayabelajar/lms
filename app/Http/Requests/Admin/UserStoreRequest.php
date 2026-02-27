<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    public function rules(): array
    {
        $role = $this->input('role');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'instructor', 'student'])],
            'nim' => [Rule::requiredIf($role === 'student'), 'nullable', 'string', 'max:50'],
            'nip' => [Rule::requiredIf(in_array($role, ['admin', 'instructor'], true)), 'nullable', 'string', 'max:50'],
            'semester' => [Rule::requiredIf($role === 'student'), 'nullable', 'string', 'max:50'],
            'angkatan' => [Rule::requiredIf($role === 'student'), 'nullable', 'string', 'max:20'],
            'kelas' => [Rule::requiredIf($role === 'student'), 'nullable', 'string', 'max:50'],
            'jurusan' => [Rule::requiredIf($role === 'student'), 'nullable', 'string', 'max:100'],
            'prodi' => [Rule::requiredIf($role === 'student'), 'nullable', 'string', 'max:100'],
        ];
    }
}
