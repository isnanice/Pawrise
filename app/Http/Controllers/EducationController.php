<?php

namespace App\Http\Controllers;

use App\Models\KontenEdukasi;
use Illuminate\Http\Request;

// Controller untuk menampilkan daftar dan detail artikel edukasi
class EducationController extends Controller
{
    // Menampilkan halaman utama edukasi dengan daftar artikel
    public function index(Request $request)
    {
        $query = KontenEdukasi::published();

        if ($kategori = $request->input('kategori')) {
            $query->byKategori($kategori);
        }

        $artikel = $query->latest()->paginate(9)->withQueryString();

        return view('education.index', compact('artikel'));
    }

    // Menampilkan halaman detail artikel edukasi dan artikel terkait
    public function show(KontenEdukasi $kontenEdukasi)
    {
        abort_unless($kontenEdukasi->is_published, 404);

        $related = KontenEdukasi::published()
            ->byKategori($kontenEdukasi->kategori)
            ->where('id', '!=', $kontenEdukasi->id)
            ->latest()
            ->take(3)
            ->get();

        return view('education.show', compact('kontenEdukasi', 'related'));
    }
}
