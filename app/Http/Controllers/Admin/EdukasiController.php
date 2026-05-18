<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontenEdukasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

// Controller untuk mengelola konten edukasi oleh admin
class EdukasiController extends Controller
{
    // Menampilkan daftar konten edukasi
    public function index(Request $request)
    {
        $query = KontenEdukasi::latest();

        if ($request->filled('q')) {
            $query->where('judul', 'like', "%{$request->q}%");
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $edukasi = $query->paginate(10)->withQueryString();

        return view('admin.edukasi.index', compact('edukasi'));
    }

    // Menampilkan halaman form pembuatan konten edukasi
    public function create()
    {
        return view('admin.edukasi.create');
    }

    // Menyimpan konten edukasi baru ke database
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'          => 'required|string|max:255',
            'ringkasan'      => 'required|string|max:500',
            'konten'         => 'required|string',
            'kategori'       => 'required|in:kesehatan,pelatihan,nutrisi,gaya_hidup,lainnya',
            'estimasi_baca'  => 'nullable|integer|min:1',
            'gambar'         => 'nullable|image|max:2048',
            'is_published'   => 'nullable|boolean',
        ]);

        if ($request->hasFile('gambar')) {
            if (config('filesystems.default') === 'cloudinary') {
                $uploaded = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::uploadApi()->upload($request->file('gambar')->getRealPath(), [
                    'folder' => 'pawrise/edukasi',
                ]);
                $data['gambar'] = $uploaded['secure_url'];
            } else {
                $data['gambar'] = $request->file('gambar')->store('edukasi', 'public');
            }
        }

        $data['slug']        = Str::slug($data['judul']);
        $data['admin_id']    = auth()->id();
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;

        KontenEdukasi::create($data);

        return redirect()->route('admin.edukasi.index')->with('success', 'Konten edukasi berhasil dibuat.');
    }

    // Menampilkan halaman form edit konten edukasi
    public function edit(KontenEdukasi $edukasi)
    {
        return view('admin.edukasi.edit', compact('edukasi'));
    }

    // Memperbarui data konten edukasi di database
    public function update(Request $request, KontenEdukasi $edukasi)
    {
        $data = $request->validate([
            'judul'          => 'required|string|max:255',
            'ringkasan'      => 'required|string|max:500',
            'konten'         => 'required|string',
            'kategori'       => 'required|in:kesehatan,pelatihan,nutrisi,gaya_hidup,lainnya',
            'estimasi_baca'  => 'nullable|integer|min:1',
            'gambar'         => 'nullable|image|max:2048',
            'is_published'   => 'nullable|boolean',
        ]);

        if ($request->hasFile('gambar')) {
            if ($edukasi->gambar && !str_starts_with($edukasi->gambar, 'http')) {
                Storage::disk('public')->delete($edukasi->gambar);
            }
            if (config('filesystems.default') === 'cloudinary') {
                $uploaded = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::uploadApi()->upload($request->file('gambar')->getRealPath(), [
                    'folder' => 'pawrise/edukasi',
                ]);
                $data['gambar'] = $uploaded['secure_url'];
            } else {
                $data['gambar'] = $request->file('gambar')->store('edukasi', 'public');
            }
        }

        $data['slug']        = Str::slug($data['judul']);
        $data['is_published'] = $request->boolean('is_published');

        if ($data['is_published'] && ! $edukasi->published_at) {
            $data['published_at'] = now();
        } elseif (! $data['is_published']) {
            $data['published_at'] = null;
        }

        $edukasi->update($data);

        return redirect()->route('admin.edukasi.index')->with('success', 'Konten edukasi berhasil diperbarui.');
    }

    // Memindahkan konten edukasi ke tempat sampah (soft delete)
    public function destroy(KontenEdukasi $edukasi)
    {
        $edukasi->delete();
        return redirect()->route('admin.edukasi.index')->with('success', 'Konten edukasi berhasil dipindahkan ke tempat sampah.');
    }

    // Menampilkan daftar konten edukasi di tempat sampah
    public function trash()
    {
        $edukasi = KontenEdukasi::onlyTrashed()->latest()->paginate(10);
        return view('admin.edukasi.trash', compact('edukasi'));
    }

    // Memulihkan konten edukasi dari tempat sampah
    public function restore($id)
    {
        $edukasi = KontenEdukasi::withTrashed()->findOrFail($id);
        $edukasi->restore();
        return redirect()->route('admin.edukasi.trash')->with('success', 'Konten edukasi berhasil dipulihkan.');
    }

    // Menghapus konten edukasi secara permanen
    public function forceDelete($id)
    {
        $edukasi = KontenEdukasi::withTrashed()->findOrFail($id);
        if ($edukasi->gambar && !str_starts_with($edukasi->gambar, 'http')) {
            Storage::disk('public')->delete($edukasi->gambar);
        }
        $edukasi->forceDelete();
        return redirect()->route('admin.edukasi.trash')->with('success', 'Konten edukasi berhasil dihapus permanen.');
    }
}
