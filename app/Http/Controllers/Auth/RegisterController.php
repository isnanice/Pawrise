<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Auto-correct gmail.con typo
        if ($request->has('email')) {
            $request->merge([
                'email' => str_replace('@gmail.con', '@gmail.com', $request->email)
            ]);
        }

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'email', 'max:160', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:30'],
            'password' => ['required', Password::min(8)],
            'role'     => ['required', 'in:adopter,shelter'],
            'shelter_name' => ['required_if:role,shelter', 'nullable', 'string', 'max:160'],
            'city'         => ['nullable', 'string', 'max:80'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        if ($user->role === 'shelter') {
            Shelter::create([
                'user_id'      => $user->id,
                'shelter_name' => $data['shelter_name'] ?? $user->name,
                'city'         => $data['city'] ?? 'Jakarta',
            ]);
        }

        Auth::login($user);

        return $user->isShelter()
            ? redirect()->route('shelter.dashboard')->with('success', 'Selamat datang! Akun shelter Anda berhasil dibuat.')
            : redirect()->route('catalog.index')->with('success', 'Selamat datang di PawRise!');
    }
}
