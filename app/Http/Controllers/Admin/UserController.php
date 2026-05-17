<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// Controller untuk mengelola data pengguna oleh admin
class UserController extends Controller
{
    // Menampilkan daftar pengguna (adopter)
    public function index(Request $request)
    {
        $query = User::where('role', 'adopter');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // Menampilkan detail informasi pengguna
    public function show(User $user)
    {
        $user->load('applications.animal', 'favorites');
        return view('admin.users.show', compact('user'));
    }

    // Menghapus akun pengguna
    public function destroy(User $user)
    {
        // Jangan hapus admin sendiri
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat menghapus akun admin.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
