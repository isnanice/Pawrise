<?php

namespace App\Http\Controllers\Shelter;

use App\Http\Controllers\Controller;
use App\Models\AdoptionApplication;
use Illuminate\Http\Request;

// Controller untuk mengelola permohonan adopsi oleh shelter
class ApplicationController extends Controller
{
    // Menampilkan daftar permohonan adopsi hewan
    public function index(Request $request)
    {
        $shelter = auth()->user()->shelter;
        abort_unless($shelter, 403);

        $query = AdoptionApplication::with(['animal', 'user'])
            ->whereHas('animal', fn($q) => $q->where('shelter_id', $shelter->id));

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $apps = $query->latest()->paginate(10)->withQueryString();
        return view('shelter.applications.index', compact('apps'));
    }

    // Menampilkan halaman detail permohonan adopsi
    public function show(AdoptionApplication $application)
    {
        $this->authorizeShelter($application);
        $application->load(['animal', 'user']);
        return view('shelter.applications.show', compact('application'));
    }

    // Menyetujui permohonan adopsi hewan
    public function approve(AdoptionApplication $application)
    {
        $this->authorizeShelter($application);
        $application->update(['status' => 'disetujui']);
        $application->animal->update(['status' => 'diadopsi']);
        return back()->with('success', 'Permohonan disetujui.');
    }

    // Menolak permohonan adopsi hewan
    public function reject(Request $request, AdoptionApplication $application)
    {
        $this->authorizeShelter($application);
        $application->update([
            'status'       => 'ditolak',
            'shelter_note' => $request->input('note'),
        ]);
        // Kembalikan status hewan ke tersedia
        $application->animal->update(['status' => 'tersedia']);
        return back()->with('success', 'Permohonan ditolak.');
    }

    // Memvalidasi apakah permohonan adopsi ditujukan untuk shelter yang bersangkutan
    private function authorizeShelter(AdoptionApplication $application): void
    {
        $shelter = auth()->user()->shelter;
        abort_unless($shelter && $application->animal->shelter_id === $shelter->id, 403);
    }
}