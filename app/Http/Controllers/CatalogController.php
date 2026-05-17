<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;

// Controller untuk menampilkan katalog pencarian hewan
class CatalogController extends Controller
{
    // Menampilkan halaman katalog hewan dengan berbagai filter pencarian
    public function index(Request $request)
    {
        $query = Animal::with('shelter')->where('status', '!=', 'diadopsi');

        if ($search = $request->string('q')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('breed', 'like', "%{$search}%");
            });
        }

        if ($species = $request->input('species', [])) {
            $query->whereIn('species', (array) $species);
        }

        if ($ages = $request->input('age', [])) {
            $query->where(function ($q) use ($ages) {
                foreach ((array) $ages as $age) {
                    if ($age === 'bayi')   $q->orWhere('age_months', '<', 6);
                    if ($age === 'muda')   $q->orWhereBetween('age_months', [6, 12]);
                    if ($age === 'dewasa') $q->orWhereBetween('age_months', [13, 60]);
                    if ($age === 'senior') $q->orWhere('age_months', '>', 60);
                }
            });
        }

        if ($gender = $request->input('gender')) {
            $query->where('gender', $gender);
        }

        if ($sizes = $request->input('size', [])) {
            $query->whereIn('size', (array) $sizes);
        }

        if ($city = $request->input('city')) {
            $query->whereHas('shelter', fn($q) => $q->where('city', $city));
        }

        $sort = $request->input('sort', 'terbaru');
        if ($sort === 'terlama') {
            $query->orderBy('id', 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $animals = $query->paginate(9)->withQueryString();

        $cities = \App\Models\Shelter::query()->distinct()->orderBy('city')->pluck('city');

        return view('catalog.index', compact('animals', 'cities'));
    }
}
