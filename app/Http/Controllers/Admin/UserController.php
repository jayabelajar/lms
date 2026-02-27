<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        $query = User::with('roles')->latest();

        if ($role = request('role')) {
            $query->role($role);
        }

        if ($status = request('status')) {
            if ($status === 'active') {
                $query->whereNull('suspended_at');
            }
            if ($status === 'suspended') {
                $query->whereNotNull('suspended_at');
            }
        }

        if ($semester = request('semester')) {
            $query->where('semester', $semester);
        }
        if ($angkatan = request('angkatan')) {
            $query->where('angkatan', $angkatan);
        }
        if ($kelas = request('kelas')) {
            $query->where('kelas', $kelas);
        }
        if ($jurusan = request('jurusan')) {
            $query->where('jurusan', $jurusan);
        }
        if ($prodi = request('prodi')) {
            $query->where('prodi', $prodi);
        }

        if ($q = request('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('nim', 'like', "%{$q}%")
                    ->orWhere('nip', 'like', "%{$q}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'nim' => $data['nim'] ?? null,
            'nip' => $data['nip'] ?? null,
            'semester' => $data['semester'] ?? null,
            'angkatan' => $data['angkatan'] ?? null,
            'kelas' => $data['kelas'] ?? null,
            'jurusan' => $data['jurusan'] ?? null,
            'prodi' => $data['prodi'] ?? null,
        ]);

        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')->with('status', 'User created.');
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'nim' => $data['nim'] ?? null,
            'nip' => $data['nip'] ?? null,
            'semester' => $data['semester'] ?? null,
            'angkatan' => $data['angkatan'] ?? null,
            'kelas' => $data['kelas'] ?? null,
            'jurusan' => $data['jurusan'] ?? null,
            'prodi' => $data['prodi'] ?? null,
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')->with('status', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }

    public function toggleSuspend(User $user): RedirectResponse
    {
        $user->suspended_at = $user->isSuspended() ? null : now();
        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'User status updated.');
    }
}
