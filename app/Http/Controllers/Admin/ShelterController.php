<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shelter;
use Illuminate\Http\Request;

// Controller untuk mengelola data shelter oleh admin
class ShelterController extends Controller
{
    // Menampilkan daftar shelter terdaftar
    public function index(Request $request)
    {
        $query = Shelter::with('user');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('shelter_name', 'like', "%$q%")
                    ->orWhere('city', 'like', "%$q%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_verified', $request->status === 'verified');
        }

        $shelters = $query->latest()->paginate(10)->withQueryString();

        return view('admin.shelters.index', compact('shelters'));
    }

    // Menampilkan halaman detail shelter
    public function show(Shelter $shelter)
    {
        $shelter->load('user', 'animals');
        return view('admin.shelters.show', compact('shelter'));
    }

    // Memverifikasi pendaftaran shelter
    public function verify(Shelter $shelter)
    {
        $shelter->update(['is_verified' => true]);
        return back()->with('success', "Shelter \"{$shelter->shelter_name}\" berhasil diverifikasi.");
    }

    // Membatalkan verifikasi shelter
    public function reject(Shelter $shelter)
    {
        $shelter->update(['is_verified' => false]);
        return back()->with('success', "Verifikasi shelter \"{$shelter->shelter_name}\" dibatalkan.");
    }

    // Menghapus shelter (soft delete)
    public function destroy(Shelter $shelter)
    {
        $shelter->delete();
        return redirect()->route('admin.shelters.index')->with('success', 'Data shelter berhasil dihapus (soft delete).');
    }

    public function trash()
    {
        $shelters = Shelter::onlyTrashed()->with('user')->latest()->paginate(10);
        return view('admin.shelters.trash', compact('shelters'));
    }

    public function restore($id)
    {
        $shelter = Shelter::onlyTrashed()->findOrFail($id);
        $shelter->restore();
        return redirect()->route('admin.shelters.trash')->with('success', 'Data shelter berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $shelter = Shelter::onlyTrashed()->findOrFail($id);
        $shelter->forceDelete();
        return redirect()->route('admin.shelters.trash')->with('success', 'Data shelter dihapus permanen.');
    }
}
