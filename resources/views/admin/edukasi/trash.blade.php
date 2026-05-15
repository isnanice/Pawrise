@extends('layouts.admin')
@section('title', 'Tempat Sampah Edukasi - PawRise Admin')
@section('content')

<style>
.daftar-subtitle { font-size: .88rem; color: var(--pr-text-muted); margin-top: 4px; }
.hw-table th {
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .07em; color: var(--pr-text-muted);
    padding: 12px 16px; border-bottom: 1.5px solid var(--pr-border); background: #fff;
}
.hw-table td { padding: 14px 16px; border-bottom: 1px solid var(--pr-border); vertical-align: middle; }
.hw-table tbody tr:last-child td { border-bottom: none; }
.hw-photo {
    width: 46px; height: 46px; border-radius: 10px;
    object-fit: cover; flex-shrink: 0; border: 2px solid var(--pr-border);
}
.hw-icon-btn {
    width: 32px; height: 32px; border-radius: 8px;
    border: 1.5px solid var(--pr-border); background: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    color: var(--pr-text-muted); font-size: .9rem; cursor: pointer;
    transition: all .15s; text-decoration: none;
}
.hw-icon-btn:hover { border-color: var(--pr-orange); color: var(--pr-orange); }
.hw-icon-btn.del:hover { border-color: #ef4444; color: #ef4444; }
.hw-icon-btn.res:hover { border-color: #10b981; color: #10b981; }
.btn-back {
    background: #f3f4f6; color: #6b7280; font-weight: 700;
    font-size: .88rem; padding: 10px 20px; border-radius: 10px;
    border: 1.5px solid #e5e7eb; text-decoration: none; white-space: nowrap;
    display: inline-flex; align-items: center; gap: 6px; transition: all .15s;
}
.btn-back:hover { background: #e5e7eb; color: var(--pr-text); }
</style>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h3 class="fw-bold mb-0">Tempat Sampah Edukasi</h3>
        <p class="daftar-subtitle">Konten edukasi yang baru saja Anda hapus bisa dipulihkan kembali di sini</p>
    </div>
    <a href="{{ route('admin.edukasi.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

{{-- Tabel --}}
<div class="pr-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table hw-table mb-0">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Tanggal Dihapus</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($edukasi as $item)
                <tr>
                    <td>
                        <img src="{{ $item->gambar_url }}" alt="" class="hw-photo">
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size: .9rem;">{{ $item->judul }}</div>
                        <div style="font-size:.72rem; color:var(--pr-text-muted);">{{ $item->kategori_label }}</div>
                    </td>
                    <td style="font-size:.82rem; color:var(--pr-text-muted);">
                        {{ $item->deleted_at->format('d M Y, H:i') }}
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <form method="POST" action="{{ route('admin.edukasi.restore', $item->id) }}" class="d-inline">
                                @csrf
                                <button class="hw-icon-btn res" type="submit" title="Pulihkan">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.edukasi.force-delete', $item->id) }}"
                                  class="d-inline" onsubmit="return confirm('Hapus permanen konten ini? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf @method('DELETE')
                                <button class="hw-icon-btn del" type="submit" title="Hapus Permanen">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-5" style="color: var(--pr-text-muted);">
                        Tempat sampah kosong.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
