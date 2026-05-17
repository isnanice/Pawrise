<?php

namespace App\Http\Controllers\Shelter;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\AdoptionApplication;

// Controller untuk dashboard utama shelter
class DashboardController extends Controller
{
    // Menampilkan halaman dashboard shelter beserta statistik data hewan dan permohonan
    public function index()
    {
        $shelter = auth()->user()->shelter;

        if (!$shelter) {
            return view('shelter.dashboard', [
                'totalAnimals'  => 0,
                'menungguCount' => 0,
                'newApps'       => 0,
                'recentApps'    => collect(),
                'animals'       => collect(),
            ]);
        }

        $totalAnimals  = $shelter->animals()->count();
        $menungguCount = AdoptionApplication::whereHas('animal', fn($q) => $q->where('shelter_id', $shelter->id))
                            ->where('status', 'menunggu')->count();
        $newApps       = AdoptionApplication::whereHas('animal', fn($q) => $q->where('shelter_id', $shelter->id))
                            ->where('created_at', '>=', now()->subDays(7))->count();
        $recentApps    = AdoptionApplication::with(['animal', 'user'])
                            ->whereHas('animal', fn($q) => $q->where('shelter_id', $shelter->id))
                            ->latest()->take(3)->get();
        $animals       = $shelter->animals()->latest()->paginate(10);

        return view('shelter.dashboard', compact(
            'totalAnimals', 'menungguCount', 'newApps', 'recentApps', 'animals'
        ));
    }
}
