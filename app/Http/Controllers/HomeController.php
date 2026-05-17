<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;

// Controller untuk halaman beranda dan statis
class HomeController extends Controller
{
    // Menampilkan halaman beranda dengan data hewan dan edukasi terbaru
    public function index()
    {
        $featured = Animal::with('shelter')
            ->where('status', 'tersedia')
            ->latest()
            ->take(6)
            ->get();

        $edukasi = \App\Models\KontenEdukasi::published()
            ->latest()
            ->take(3)
            ->get();

        return view('home.landing', compact('featured', 'edukasi'));
    }

    // Menampilkan halaman tentang kami
    public function about()
    {
        return view('home.about');
    }

    // Menampilkan halaman bantuan/FAQ
    public function help()
    {
        return view('home.help');
    }

    // Memproses pengiriman pesan kontak dari pengunjung
    public function sendContact(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:80',
            'last_name'  => 'nullable|string|max:80',
            'email'      => 'required|email',
            'subject'    => 'required|string|max:120',
            'message'    => 'required|string|max:2000',
        ]);

        return redirect()->route('home')
            ->with('success', 'Pesan Anda telah terkirim. Terima kasih telah menghubungi kami.');
    }

    // Menampilkan halaman kebijakan privasi
    public function privacy()
    {
        return view('home.privacy');
    }

    // Menampilkan halaman syarat dan ketentuan
    public function terms()
    {
        return view('home.terms');
    }

    // Menampilkan halaman hubungi shelter
    public function shelterContact()
    {
        return view('home.shelter_contact');
    }

    // Menampilkan halaman program relawan
    public function volunteer()
    {
        return view('home.volunteer');
    }

    // Menampilkan profil shelter dan daftar hewan yang dikelolanya
    public function shelterProfile(\App\Models\Shelter $shelter)
    {
        $animals = $shelter->animals()
            ->where('status', 'tersedia')
            ->latest()
            ->paginate(6);

        return view('home.shelter_profile', compact('shelter', 'animals'));
    }
}
