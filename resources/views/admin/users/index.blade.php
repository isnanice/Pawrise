@extends('layouts.admin')
@section('title', 'Kelola Pengguna - PawRise Admin')
@section('content')

<style>
.daftar-subtitle {
    font-size: .88rem;
    color: var(--pr-text-muted);
    margin-top: 4px;
}
.hewan-search {
    position: relative;
    flex: 1;
    min-width: 220px;
}
.hewan-search i {
    position: absolute;
    left: 13px; top: 50%;
    transform: translateY(-50%);
    color: var(--pr-text-muted);
    font-size: .95rem;
    pointer-events: none;
}
.hewan-search input {
    border: 1.5px solid var(--pr-border);
    border-radius: 10px;
    padding: 9px 14px 9px 36px;
    font-size: .88rem;
    width: 100%;
    outline: none;
    transition: border-color .15s;
}
.hewan-search input:focus { border-color: var(--pr-orange); }
.hewan-search input::placeholder { color: #bbb; }
.hw-table th {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--pr-text-muted);
    padding: 12px 16px;
    border-bottom: 1.5px solid var(--pr-border);
    background: #fff;
}
.hw-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--pr-border);
    vertical-align: middle;
}
.hw-table tbody tr:last-child td { border-bottom: none; }
.hw-table tbody tr:hover { background: var(--pr-bg); }
.hw-photo {
    width: 46px; height: 46px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid var(--pr-border);
}
.hw-icon-btn {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1.5px solid var(--pr-border);
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--pr-text-muted);
    font-size: .9rem;
    cursor: pointer;
    transition: all .15s;
    text-decoration: none;
}
.hw-icon-btn:hover { border-color: var(--pr-orange); color: var(--pr-orange); }
.hw-icon-btn.del:hover { border-color: #ef4444; color: #ef4444; }
.paw-pagination {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    margin-top: 16px;
}
.paw-page-btn {
    padding: 6px 14px;
    border-radius: 8px;
    border: 1.5px solid var(--pr-border);
    background: #fff;
    font-size: .84rem;
    font-weight: 600;
    color: var(--pr-text);
    cursor: pointer;
    text-decoration: none;
    transition: all .15s;
}
.paw-page-btn.active { background: var(--pr-orange); color: #fff; border-color: var(--pr-orange); }
.paw-page-btn:hover:not(.active) { border-color: var(--pr-orange); color: var(--pr-orange); }
.paw-page-btn.disabled { opacity: .4; pointer-events: none; }
</style>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h3 class="fw-bold mb-0">Kelola Data Pengguna</h3>
        <p class="daftar-subtitle">Kelola seluruh akun pengguna (adopter) di sistem PawRise</p>
    </div>
    <a href="{{ route('admin.users.trash') }}" class="btn btn-secondary d-inline-flex align-items-center gap-2" style="background: #f3f4f6; color: #6b7280; font-weight: 700; font-size: .88rem; padding: 10px 20px; border-radius: 10px; border: 1.5px solid #e5e7eb; text-decoration: none; white-space: nowrap; transition: all .15s;" onmouseover="this.style.background='#e5e7eb'; this.style.color='var(--pr-text)';" onmouseout="this.style.background='#f3f4f6'; this.style.color='#6b7280';">
        <i class="bi bi-archive"></i> Arsip
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-3">{{ session('error') }}</div>
@endif

{{-- Search --}}
<form method="GET" class="d-flex gap-2 flex-wrap mb-4 align-items-center">
    <div class="hewan-search">
        <i class="bi bi-search"></i>
        <input name="q" value="{{ request('q') }}" placeholder="Cari nama atau email pengguna...">
    </div>
    <button type="submit" style="display:none;"></button>
</form>

{{-- Tabel --}}
<div class="pr-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table hw-table mb-0">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Bergabung</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <img src="{{ $user->profilePhotoUrl() }}" alt="" class="hw-photo">
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size: .9rem;">{{ $user->name }}</div>
                    </td>
                    <td style="font-size: .88rem;">{{ $user->email }}</td>
                    <td style="font-size: .88rem;">{{ $user->phone ?? '-' }}</td>
                    <td style="font-size: .82rem; color:var(--pr-text-muted);">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.users.show', $user) }}" class="hw-icon-btn">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  class="d-inline" onsubmit="return confirm('Hapus akun pengguna ini?')">
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
                        <i class="bi bi-emoji-frown" style="font-size:2.5rem; display:block; margin-bottom:10px; opacity:.4;"></i>
                        Belum ada pengguna terdaftar.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="paw-pagination">
    @if($users->onFirstPage())
        <span class="paw-page-btn disabled">Prev</span>
    @else
        <a href="{{ $users->previousPageUrl() }}" class="paw-page-btn">Prev</a>
    @endif

    @for($page = 1; $page <= $users->lastPage(); $page++)
        <a href="{{ $users->url($page) }}"
           class="paw-page-btn {{ $page == $users->currentPage() ? 'active' : '' }}">
            {{ $page }}
        </a>
    @endfor

    @if($users->hasMorePages())
        <a href="{{ $users->nextPageUrl() }}" class="paw-page-btn">Next</a>
    @else
        <span class="paw-page-btn disabled">Next</span>
    @endif
</div>

@endsection
