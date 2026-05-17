<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use Illuminate\Http\Request;

// Controller untuk menampilkan halaman profil shelter
class ShelterProfileController extends Controller
{
    // Menampilkan profil detail shelter beserta hewan-hewan yang tersedia
    public function show(Shelter $shelter)
    {
        $shelter->load('user');

        $animals = $shelter->animals()
            ->where('status', 'tersedia')
            ->latest()
            ->paginate(12);

        return view('shelters.show', compact('shelter', 'animals'));
    }
}
