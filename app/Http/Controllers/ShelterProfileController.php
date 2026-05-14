<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use Illuminate\Http\Request;

class ShelterProfileController extends Controller
{
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
