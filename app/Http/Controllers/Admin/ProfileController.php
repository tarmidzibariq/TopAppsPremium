<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile.edit');
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'required|email|max:40|unique:users,email,' . auth()->id(),
        ]);
        $user = User::findOrFail(auth()->id());
        $user->update($validated);
        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
    public function password()
    {
        return view('admin.profile.password');
    }
    public function updatePassword(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'password_old' => ['required', 'string'],
            'password_new' => [
                'required', 
                'string', 
                'min:8', // Minimal 8 karakter
                'confirmed'
            ],
        ], [
            // Custom pesan error bahasa Indonesia
            'password_old.required' => 'Password lama wajib diisi.',
            'password_new.required' => 'Password baru wajib diisi.',
            'password_new.min'      => 'Password baru minimal harus 8 karakter.',
            'password_new.confirmed'=> 'Konfirmasi password baru tidak cocok.',
        ]);
        $user = User::findOrFail(auth()->id());
        if (!Hash::check($validated['password_old'], $user->password)) {
            return redirect()->back()->with('error', 'Password saat ini tidak sesuai.');
        }
        $user->update([
            'password' => Hash::make($validated['password_new'])
        ]);
        return redirect()->route('profile.password')->with('success', 'Password berhasil diperbarui.');
    }
}

