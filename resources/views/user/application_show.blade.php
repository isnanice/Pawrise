@extends('layouts.user')
@section('title', 'Detail Permohonan Adopsi - PawRise')
@section('content')

<style>
    .app-detail-card {
        background: #fff;
        border: 1px solid var(--pr-border);
        border-radius: 16px;
        padding: 24px;
    }

    .animal-preview-card {
        background: #fff;
        border: 1px solid var(--pr-border);
        border-radius: 16px;
        overflow: hidden;
    }

    .animal-preview-img {
        width: 100%;
        height: 240px;
        object-fit: cover;
    }

    .detail-row {
        display: flex;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid var(--pr-border);
    }
    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        width: 140px;
        font-weight: 700;
        color: var(--pr-text);
        font-size: .9rem;
        flex-shrink: 0;
    }

    .detail-value {
        flex-grow: 1;
        color: var(--pr-text-muted);
        font-size: .9rem;
        line-height: 1.5;
    }

    .badge-status {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 700;
    }

    .badge-menunggu { background: #FBBF24; color: #fff; }
    .badge-disetujui { background: #34D399; color: #fff; }
    .badge-ditolak { background: #EF4444; color: #fff; }
</style>

<div class="mb-4">
    <a href="{{ route('user.applications') }}" class="text-decoration-none" style="color: var(--pr-text-muted); font-size: .95rem; font-weight: 500;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    {{-- Kolom Kiri: Profil Hewan --}}
    <div class="col-12 col-md-4">
        <div class="animal-preview-card">
            <img src="{{ $application->animal->mainPhotoUrl() }}" alt="{{ $application->animal->name }}" class="animal-preview-img">
            <div class="p-3">
                <h5 class="fw-bold mb-1">{{ $application->animal->name }}</h5>
                <p class="mb-0 text-muted" style="font-size: .85rem;">
                    {{ $application->animal->breed }} &bull; 
                    {{ floor($application->animal->age_months / 12) > 0 ? floor($application->animal->age_months / 12) . ' Thn ' : '' }}
                    {{ $application->animal->age_months % 12 > 0 ? ($application->animal->age_months % 12) . ' Bln' : '' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Detail Permohonan --}}
    <div class="col-12 col-md-8">
        <div class="app-detail-card h-100 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Detail Permohonan</h4>
                
                @if($application->status === 'menunggu')
                    <span class="badge-status badge-menunggu">Menunggu</span>
                @elseif($application->status === 'disetujui')
                    <span class="badge-status badge-disetujui">Disetujui</span>
                @else
                    <span class="badge-status badge-ditolak">Ditolak</span>
                @endif
            </div>

            <div class="flex-grow-1">
                <div class="detail-row">
                    <div class="detail-label">Nama</div>
                    <div class="detail-value">{{ $application->full_name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">WhatsApp</div>
                    <div class="detail-value">{{ $application->whatsapp }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ $application->email }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Alamat</div>
                    <div class="detail-value">{{ $application->address }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Pengalaman</div>
                    <div class="detail-value">{{ ucfirst($application->experience) }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Alasan</div>
                    <div class="detail-value">{{ $application->reason }}</div>
                </div>
                <div class="detail-row border-bottom-0 pb-0">
                    <div class="detail-label">Diajukan</div>
                    <div class="detail-value">{{ $application->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>

            <div class="mt-4 pt-3 text-end" style="border-top: 1px solid var(--pr-border);">
                @if($application->status === 'disetujui')
                    @php
                        $phone = $application->animal->shelter?->phone;
                        $phone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
                        $phone = $phone && str_starts_with($phone, '0') ? '62' . substr($phone, 1) : $phone;
                        $waMsg = urlencode('Halo, saya ' . $application->full_name . ' ingin menjadwalkan penjemputan untuk ' . $application->animal->name . '. Permohonan adopsi saya telah disetujui.');
                    @endphp
                    <a href="{{ $phone ? 'https://wa.me/' . $phone . '?text=' . $waMsg : '#' }}" 
                       target="_blank" class="btn pr-btn-primary px-4 py-2" style="border-radius: 10px; font-weight: 600;">
                        Langkah Selanjutnya via Chat
                    </a>
                @elseif($application->status === 'ditolak')
                    <a href="{{ route('catalog.index') }}" class="btn px-4 py-2" style="background: #fff; border: 1px solid #ef4444; color: #ef4444; border-radius: 10px; font-weight: 600;">
                        Lihat Hewan Lain
                    </a>
                @else
                    {{-- Kosong karena jika menunggu belum ada aksi dari pengguna --}}
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
