<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Shelter;
use App\Models\KontenEdukasi;
use App\Models\AdoptionApplication;

// Controller untuk dashboard utama admin
class DashboardController extends Controller
{
    // Menampilkan halaman dashboard admin beserta statistik dan data terbaru
    public function index()
    {
        $stats = [
            'total_users'       => User::where('role', 'adopter')->count(),
            'total_shelters'    => Shelter::count(),
            'pending_shelters'  => Shelter::where('is_verified', false)->count(),
            'total_edukasi'     => KontenEdukasi::count(),
            'published_edukasi' => KontenEdukasi::where('is_published', true)->count(),
            'total_apps'        => AdoptionApplication::count(),
        ];

        $recentUsers = User::where('role', 'adopter')
            ->latest()
            ->take(5)
            ->get();

        $pendingShelters = Shelter::with('user')
            ->where('is_verified', false)
            ->latest()
            ->take(5)
            ->get();

        $recentEdukasi = KontenEdukasi::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'pendingShelters', 'recentEdukasi'));
    }
}
