<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'ob')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'ob',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun OB berhasil dibuat.');
    }

    public function edit(User $user)
    {
        if ($user->role !== 'ob') {
            abort(403, 'Tidak dapat mengedit akun admin.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'ob') {
            abort(403, 'Tidak dapat mengedit akun admin.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun OB berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'ob') {
            abort(403, 'Tidak dapat menghapus akun admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun OB berhasil dihapus.');
    }
}