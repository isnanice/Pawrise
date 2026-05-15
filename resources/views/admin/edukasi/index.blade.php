@extends('layouts.admin')
@section('title', 'Kelola Konten Edukasi - PawRise Admin')
@section('content')

<style>
.daftar-subtitle { font-size: .88rem; color: var(--pr-text-muted); margin-top: 4px; }
.hewan-search { position: relative; flex: 1; min-width: 220px; }
.hewan-search i {
    position: absolute; left: 13px; top: 50%;
    transform: translateY(-50%); color: var(--pr-text-muted);
    font-size: .95rem; pointer-events: none;
}
.hewan-search input {
    border: 1.5px solid var(--pr-border); border-radius: 10px;
    padding: 9px 14px 9px 36px; font-size: .88rem; width: 100%;
    outline: none; transition: border-color .15s;
}
.hewan-search input:focus { border-color: var(--pr-orange); }
.hewan-search input::placeholder { color: #bbb; }
.hewan-select {
    border: 1.5px solid var(--pr-border); border-radius: 10px;
    padding: 9px 32px 9px 14px; font-size: .88rem; color: var(--pr-text);
    outline: none; background: #fff; transition: border-color .15s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%23999' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center; cursor: pointer;
}
.hewan-select:focus { border-color: var(--pr-orange); }
.hw-table th {
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .07em; color: var(--pr-text-muted);
    padding: 12px 16px; border-bottom: 1.5px solid var(--pr-border); background: #fff;
}
.hw-table td { padding: 14px 16px; border-bottom: 1px solid var(--pr-border); vertical-align: middle; }
.hw-table tbody tr:last-child td { border-bottom: none; }
.hw-table tbody tr:hover { background: var(--pr-bg); }
.hw-photo {
    width: 46px; height: 46px; border-radius: 10px;
    object-fit: cover; flex-shrink: 0; border: 2px solid var(--pr-border);
}
.hw-pill {
    padding: 4px 14px; border-radius: 999px; font-size: .75rem; font-weight: 700; display: inline-block;
}
.pill-published { background: #D1FAE5; color: #065F46; }
.pill-draft     { background: #F3F4F6; color: #6B7280; }
.hw-icon-btn {
    width: 32px; height: 32px; border-radius: 8px;
    border: 1.5px solid var(--pr-border); background: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    color: var(--pr-text-muted); font-size: .9rem; cursor: pointer;
    transition: all .15s; text-decoration: none;
}
.hw-icon-btn:hover { border-color: var(--pr-orange); color: var(--pr-orange); }
.hw-icon-btn.del:hover { border-color: #ef4444; color: #ef4444; }
.btn-tambah {
    background: var(--pr-orange); color: #fff; font-weight: 700;
    font-size: .88rem; padding: 10px 20px; border-radius: 10px;
    border: none; text-decoration: none; white-space: nowrap;
    display: inline-flex; align-items: center; gap: 6px; transition: background .15s;
}
.btn-tambah:hover { background: var(--pr-orange-dark); color: #fff; }
.paw-pagination {
    display: flex; align-items: center; justify-content: flex-end; gap: 6px; margin-top: 16px;
}
.paw-page-btn {
    padding: 6px 14px; border-radius: 8px; border: 1.5px solid var(--pr-border);
    background: #fff; font-size: .84rem; font-weight: 600; color: var(--pr-text);
    cursor: pointer; text-decoration: none; transition: all .15s;
}
.paw-page-btn.active { background: var(--pr-orange); color: #fff; border-color: var(--pr-orange); }
.paw-page-btn:hover:not(.active) { border-color: var(--pr-orange); color: var(--pr-orange); }
.paw-page-btn.disabled { opacity: .4; pointer-events: none; }
.kategori-badge {
    padding: 3px 10px; border-radius: 999px; font-size: .72rem; font-weight: 700;
    background: #E0E7FF; color: #4338CA; display: inline-block;
}
</style>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h3 class="fw-bold mb-0">Kelola Konten Edukasi</h3>
        <p class="daftar-subtitle">Buat, edit, dan kelola artikel edukasi untuk pengguna PawRise</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.edukasi.trash') }}" class="btn-tambah" style="background: #f3f4f6; color: #6b7280; border: 1.5px solid #e5e7eb;">
            <i class="bi bi-trash"></i> Tempat Sampah
        </a>
        <a href="{{ route('admin.edukasi.create') }}" class="btn-tambah">
            <i class="bi bi-plus-lg"></i> Tambah Konten Baru
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

{{-- Search & Filter --}}
<form method="GET" class="d-flex gap-2 flex-wrap mb-4 align-items-center">
    <div class="hewan-search">
        <i class="bi bi-search"></i>
        <input name="q" value="{{ request('q') }}" placeholder="Cari judul artikel...">
    </div>
    <select name="kategori" class="hewan-select" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        @foreach(['kesehatan','pelatihan','nutrisi','gaya_hidup','lainnya'] as $kat)
            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$kat)) }}</option>
        @endforeach
    </select>
    <select name="status" class="hewan-select" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasi</option>
        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
    </select>
    <button type="submit" style="display:none;"></button>
</form>

{{-- Tabel --}}
<div class="pr-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table hw-table mb-0">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal</th>
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
                        <div class="fw-semibold" style="font-size: .9rem; max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ $item->judul }}
                        </div>
                        <div style="font-size:.72rem; color:var(--pr-text-muted); margin-top:2px;">
                            {{ $item->estimasi_baca ?? '?' }} menit baca
                        </div>
                    </td>
                    <td>
                        <span class="kategori-badge">{{ $item->kategori_label }}</span>
                    </td>
                    <td>
                        @if($item->is_published)
                            <span class="hw-pill pill-published">Dipublikasi</span>
                        @else
                            <span class="hw-pill pill-draft">Draft</span>
                        @endif
                    </td>
                    <td style="font-size:.82rem; color:var(--pr-text-muted);">
                        {{ $item->created_at->format('d M Y') }}
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.edukasi.edit', $item) }}" class="hw-icon-btn">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.edukasi.destroy', $item) }}"
                                  class="d-inline" onsubmit="return confirm('Hapus konten edukasi ini?')">
                                @csrf @method('DELETE')
                                <button class="hw-icon-btn del" type="submit">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5" style="color: var(--pr-text-muted);">
                        <i class="bi bi-journal-x" style="font-size:2.5rem; display:block; margin-bottom:10px; opacity:.4;"></i>
                        Belum ada konten edukasi.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="paw-pagination">
    @if($edukasi->onFirstPage())
        <span class="paw-page-btn disabled">Prev</span>
    @else
        <a href="{{ $edukasi->previousPageUrl() }}" class="paw-page-btn">Prev</a>
    @endif

    @for($page = 1; $page <= $edukasi->lastPage(); $page++)
        <a href="{{ $edukasi->url($page) }}"
           class="paw-page-btn {{ $page == $edukasi->currentPage() ? 'active' : '' }}">
            {{ $page }}
        </a>
    @endfor

    @if($edukasi->hasMorePages())
        <a href="{{ $edukasi->nextPageUrl() }}" class="paw-page-btn">Next</a>
    @else
        <span class="paw-page-btn disabled">Next</span>
    @endif
</div>

@endsection
