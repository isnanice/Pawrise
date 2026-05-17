<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Favorite;

// Controller untuk mengelola hewan favorit pengguna
class FavoriteController extends Controller
{
    // Menambah atau menghapus hewan dari daftar favorit pengguna
    public function toggle(Animal $animal)
    {
        $existing = Favorite::where('user_id', auth()->id())
            ->where('animal_id', $animal->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Dihapus dari favorit.');
        }

        Favorite::create([
            'user_id' => auth()->id(),
            'animal_id' => $animal->id,
        ]);

        return back()->with('success', 'Ditambahkan ke favorit.');
    }

    // Menampilkan halaman daftar hewan terfavorit pengguna
    public function index()
    {
        $animals = auth()->user()->favoriteAnimals()
            ->with('shelter')
            ->latest('favorites.created_at')
            ->paginate(8);

        return view('user.favorites', compact('animals'));
    }
}
