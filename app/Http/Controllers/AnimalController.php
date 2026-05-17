<?php

namespace App\Http\Controllers;

use App\Models\Animal;

// Controller untuk menampilkan detail hewan
class AnimalController extends Controller
{
    // Menampilkan halaman detail hewan beserta hewan sejenis
    public function show(Animal $animal)
    {
        $animal->load(['shelter', 'photos']);

        $similar = Animal::with('shelter')
            ->where('id', '!=', $animal->id)
            ->where('species', $animal->species)
            ->where('status', 'tersedia')
            ->take(4)
            ->get();

        return view('animals.show', compact('animal', 'similar'));
    }
}
