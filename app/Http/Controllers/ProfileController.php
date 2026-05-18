<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// Controller untuk mengelola profil pengguna
class ProfileController extends Controller
{
    // Menampilkan halaman form edit profil
    public function edit()
    {
        return view('user.profile');
    }

    // Memperbarui data profil pengguna
    public function update(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:160'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'bio'     => ['nullable', 'string', 'max:2000'],
            'photo'   => ['nullable', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        if ($request->hasFile('photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('photo')->store('avatars', 'public');
        }

        unset($data['photo']);
        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
