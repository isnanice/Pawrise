<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Controller untuk mengelola proses keluar (logout) pengguna
class LogoutController extends Controller
{
    // Menampilkan halaman konfirmasi keluar (logout)
    public function show()
    {
        $user = auth()->user();
        if ($user && $user->isAdmin()) {
            return view('admin.logout');
        }
        if ($user && $user->isShelter()) {
            return view('shelter.logout');
        }
        return view('user.logout');
    }

    // Memproses proses keluar dan membersihkan sesi pengguna
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda telah keluar.');
    }
}
