@extends('layouts.admin')
@section('title', 'Dashboard Admin - PawRise')
@section('content')

<style>
.dash-stat {
    background: #fff;
    border: 1px solid var(--pr-border);
    border-radius: 16px;
    padding: 20px 24px;
}
.dash-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    margin-bottom: 10px;
}
.dash-stat-num {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 4px;
}
.dash-stat-label {
    font-size: .78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--pr-text-muted);
}
.app-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--pr-border);
}
.app-row:last-child { border-bottom: none; }
.app-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: .9rem;
    flex-shrink: 0;
}
.status-pill {
    padding: 3px 10px;
    border-radius: 999px;
    font-size: .75rem;
    font-weight: 700;
    white-space: nowrap;
}
.pill-verified   { background: #D1FAE5; color: #065F46; }
.pill-unverified { background: #FEF3C7; color: #92400E; }
.pill-published  { background: #D1FAE5; color: #065F46; }
.pill-draft      { background: #F3F4F6; color: #6B7280; }
.performa-card {
    background: #fff;
    border: 1px solid var(--pr-border);
    border-radius: 16px;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}
</style>

{{-- Header --}}
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h3 class="fw-bold mb-1">Selamat datang, {{ auth()->user()->name }}</h3>
        <p class="mb-0" style="color: var(--pr-text-muted); font-size: .9rem;">
            Berikut adalah ringkasan keseluruhan sistem PawRise.
        </p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    {{-- Total Pengguna --}}
    <div class="col-md-4">
        <div class="dash-stat text-center">
            <div class="dash-stat-icon" style="background: #DBEAFE; margin: 0 auto 12px;">
                <i class="bi bi-people-fill" style="color: #3B82F6;"></i>
            </div>
            <div class="dash-stat-label">TOTAL PENGGUNA</div>
            <div class="dash-stat-num" style="color: var(--pr-text);">{{ $stats['total_users'] }}</div>
            <div style="font-size:.82rem; color: #16A34A; font-weight: 600; margin-top:4px;">
                <i class="bi bi-person-check"></i> Akun adopter terdaftar
            </div>
        </div>
    </div>

    {{-- Shelter Menunggu Verifikasi --}}
    <div class="col-md-4">
        <div class="dash-stat text-center" style="border: 2px solid var(--pr-orange);">
            <div class="dash-stat-icon" style="background: #FEF3C7; margin: 0 auto 12px;">
                <i class="bi bi-shop" style="color: var(--pr-orange);"></i>
            </div>
            <div class="dash-stat-label">MENUNGGU VERIFIKASI</div>
            <div class="dash-stat-num" style="color: var(--pr-text);">{{ $stats['pending_shelters'] }}</div>
            <div style="font-size:.82rem; color: var(--pr-orange); font-weight: 700; margin-top:4px;">
                @if($stats['pending_shelters'] > 0)
                    Butuh tindakan segera
                @else
                    Semua shelter terverifikasi
                @endif
            </div>
        </div>
    </div>

    {{-- Total Konten Edukasi --}}
    <div class="col-md-4">
        <div class="dash-stat text-center">
            <div class="dash-stat-icon" style="background: #E0E7FF; margin: 0 auto 12px;">
                <i class="bi bi-journal-richtext" style="color: #6366F1;"></i>
            </div>
            <div class="dash-stat-label">KONTEN EDUKASI</div>
            <div class="dash-stat-num" style="color: var(--pr-text);">{{ $stats['total_edukasi'] }}</div>
            <div style="font-size:.82rem; color: #16A34A; font-weight: 600; margin-top:4px;">
                <i class="bi bi-check-circle"></i> {{ $stats['published_edukasi'] }} sudah dipublikasi
            </div>
        </div>
    </div>
</div>

{{-- Pengguna Terbaru + Shelter Pending --}}
<div class="row g-3 mb-4">

    {{-- Pengguna Terbaru --}}
    <div class="col-lg-6">
        <div class="pr-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Pengguna Terbaru</h6>
                <a href="{{ route('admin.users.index') }}"
                   style="font-size:.83rem; color:var(--pr-orange); font-weight:600; text-decoration:none;">
                    Lihat Semua
                </a>
            </div>

            @forelse($recentUsers as $user)
            <div class="app-row">
                <div class="app-avatar" style="background: var(--pr-orange-light); color: var(--pr-orange); font-size:.85rem; font-weight:800;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-grow-1" style="min-width:0;">
                    <div class="fw-semibold" style="font-size:.88rem;">{{ $user->name }}</div>
                    <div style="font-size:.75rem; color:var(--pr-text-muted);">
                        {{ $user->email }}
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <span style="font-size:.72rem; color:var(--pr-text-muted);">{{ $user->created_at->diffForHumans() }}</span>
                    <a href="{{ route('admin.users.show', $user) }}"
                       style="width:28px;height:28px;border-radius:8px;border:1px solid var(--pr-border);
                              display:flex;align-items:center;justify-content:center;
                              color:var(--pr-text-muted);text-decoration:none;font-size:.8rem;">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
            @empty
            <p class="text-center py-3 mb-0" style="color:var(--pr-text-muted); font-size:.9rem;">
                Belum ada pengguna terdaftar.
            </p>
            @endforelse
        </div>
    </div>

    {{-- Shelter Menunggu Verifikasi --}}
    <div class="col-lg-6">
        <div class="pr-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Shelter Menunggu Verifikasi</h6>
                <a href="{{ route('admin.shelters.index') }}"
                   style="font-size:.83rem; color:var(--pr-text-muted); text-decoration:none;">
                    <i class="bi bi-three-dots"></i>
                </a>
            </div>

            @forelse($pendingShelters as $shelter)
            <div class="app-row">
                <img src="{{ $shelter->logoUrl() }}" alt=""
                     style="width:42px;height:42px;border-radius:50%;object-fit:cover;flex-shrink:0;border:2px solid var(--pr-border);">
                <div class="flex-grow-1" style="min-width:0;">
                    <div class="fw-semibold" style="font-size:.88rem;">{{ $shelter->shelter_name }}</div>
                    <div style="font-size:.75rem; color:var(--pr-text-muted);">
                        {{ $shelter->city }} &bull; {{ $shelter->user->email ?? '-' }}
                    </div>
                </div>
                <span class="status-pill pill-unverified">Menunggu</span>
            </div>
            @empty
            <p class="text-center py-3 mb-0" style="color:var(--pr-text-muted); font-size:.9rem;">
                Tidak ada shelter menunggu verifikasi.
            </p>
            @endforelse

            <a href="{{ route('admin.shelters.index') }}"
               class="btn w-100 mt-3"
               style="border-radius:10px;border:1px solid var(--pr-border);font-size:.85rem;
                      color:var(--pr-text-muted);font-weight:600;padding:8px;">
                Kelola Semua Shelter
            </a>
        </div>
    </div>
</div>

{{-- Info ringkasan --}}
<div class="performa-card" style="background: #EFF6FF; border: 1px solid #BFDBFE;">
    <div style="width:44px;height:44px;border-radius:12px;background:#DBEAFE;
                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="bi bi-graph-up-arrow" style="color:#3B82F6;font-size:1.2rem;"></i>
    </div>
    <div class="flex-grow-1">
        <div class="fw-bold mb-1" style="font-size:.95rem;">Ringkasan Sistem PawRise</div>
        <div style="font-size:.83rem; color:var(--pr-text-muted);">
            Platform telah memfasilitasi
            <span style="color:#16A34A; font-weight:700;">{{ $stats['total_apps'] }} permohonan adopsi</span>
            dengan <span style="color:var(--pr-orange); font-weight:700;">{{ $stats['total_shelters'] }} shelter</span> terdaftar.
        </div>
    </div>
    <div class="d-flex gap-4 flex-shrink-0">
        <div class="text-center">
            <div style="font-size:.72rem;color:var(--pr-text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Total Shelter</div>
            <div class="fw-bold" style="font-size:1.4rem; color:var(--pr-text);">{{ $stats['total_shelters'] }}</div>
        </div>
        <div class="text-center">
            <div style="font-size:.72rem;color:var(--pr-text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Total Adopsi</div>
            <div class="fw-bold" style="font-size:1.4rem; color:var(--pr-text);">{{ $stats['total_apps'] }}</div>
        </div>
    </div>
</div>

@endsection
