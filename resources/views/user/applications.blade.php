@extends('layouts.user')
@section('title', 'Status Adopsi - PawRise')
@section('content')

<style>
    .app-card {
        background: #fff;
        border: 1px solid var(--pr-border);
        border-radius: 16px;
        padding: 16px;
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    .app-card-img {
        width: 110px;
        height: 90px;
        object-fit: cover;
        border-radius: 12px;
        flex-shrink: 0;
    }

    .app-status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 700;
    }

    .badge-disetujui {
        background: #D1FAE5;
        color: #065F46;
    }

    .badge-menunggu {
        background: #92400E;
        color: #fff;
    }

    .badge-ditolak {
        background: #991B1B;
        color: #fff;
    }

    .badge-draft {
        background: #F3F4F6;
        color: #374151;
        border: 1px solid #D1D5DB;
    }
</style>

<h3 class="fw-bold mb-1">Status Adopsi</h3>
<p class="mb-4" style="color: var(--pr-text-muted); font-size: .92rem;">
    Pantau perkembangan pengajuan adopsi Anda secara real-time.
</p>

@if(session('success'))
<div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

@if($apps->count())
<div class="d-flex flex-column gap-3">
    @foreach($apps as $app)
    <div class="app-card">

        {{-- Foto hewan --}}
        <img src="{{ $app->animal->mainPhotoUrl() }}"
            alt="{{ $app->animal->name }}"
            class="app-card-img">

        {{-- Info --}}
        <div class="flex-grow-1 min-width-0">
            <div class="d-flex align-items-start justify-content-between gap-2 mb-1 flex-wrap">
                <div>
                    <h6 class="fw-bold mb-0">{{ $app->animal->name }}</h6>
                    <div style="font-size:.82rem; color: var(--pr-text-muted);">
                        {{ ucfirst($app->animal->species) }} •
                        {{ $app->animal->breed }} •
                        {{ $app->animal->shelter->shelter_name ?? '' }}
                        @if($app->animal->shelter?->city), {{ $app->animal->shelter->city }}@endif
                    </div>
                </div>
                {{-- Badge status --}}
                <span class="app-status-badge
                        @if($app->status === 'disetujui') badge-disetujui
                        @elseif($app->status === 'ditolak') badge-ditolak
                        @elseif($app->status === 'draft') badge-draft
                        @else badge-menunggu
                        @endif">
                    @if($app->status === 'disetujui') ✓ Disetujui
                    @elseif($app->status === 'ditolak') ✕ Ditolak
                    @elseif($app->status === 'draft') 📝 Draft
                    @else ⏳ Menunggu
                    @endif
                </span>
            </div>

            {{-- Pesan status --}}
            <div class="p-2 rounded-2 mb-3 mt-2"
                style="background: var(--pr-bg); font-size: .83rem; color: var(--pr-text-muted); line-height: 1.55;">
                <span class="fw-semibold" style="color: var(--pr-text);">Pembaruan Terakhir:</span>
                @if($app->status === 'disetujui')
                Pengajuan Anda telah disetujui oleh shelter. Silakan jadwalkan penjemputan.
                @elseif($app->status === 'ditolak')
                Sayangnya pengajuan Anda tidak dapat diizinkan saat ini karena lingkungan tempat tinggal tidak memenuhi kriteria khusus {{ $app->animal->name }}.
                @elseif($app->status === 'draft')
                Draft tersimpan. Lanjutkan dan ajukan permohonan kapan saja.
                @else
                Dokumen sedang ditinjau. Tim kami akan menghubungi Anda dalam 1-2 hari kerja untuk wawancara singkat.
                @endif
            </div>

            {{-- Tombol aksi --}}
            @if($app->status === 'disetujui')
            @php
            $phone = $app->animal->shelter?->phone;
            $phone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
            $phone = $phone && str_starts_with($phone, '0') ? '62' . substr($phone, 1) : $phone;
            $waMsg = urlencode('Halo, saya ' . $app->full_name . ' ingin menjadwalkan penjemputan untuk ' . $app->animal->name . '. Permohonan adopsi saya telah disetujui.');
            @endphp
            <a href="{{ $phone ? 'https://wa.me/' . $phone . '?text=' . $waMsg : '#' }}"
                target="_blank"
                class="btn btn-sm pr-btn-primary"
                style="border-radius: 10px; font-size: .85rem; padding: 6px 18px;">
                <i class="bi bi-whatsapp me-1"></i> Langkah Selanjutnya
            </a>
            @elseif($app->status === 'ditolak')
            <a href="{{ route('catalog.index') }}"
                class="btn btn-sm pr-btn-primary"
                style="border-radius: 10px; font-size: .85rem; padding: 6px 18px;">
                Lihat Hewan Lain
            </a>
            @elseif($app->status === 'draft')
            <a href="{{ route('adoption.create', $app->animal) }}"
                class="btn btn-sm pr-btn-primary"
                style="border-radius: 10px; font-size: .85rem; padding: 6px 18px;">
                <i class="bi bi-pencil me-1"></i> Lanjutkan Draft
            </a>
            @else
            <a href="{{ route('animals.show', $app->animal) }}"
                class="btn btn-sm pr-btn-primary"
                style="border-radius: 10px; font-size: .85rem; padding: 6px 18px;">
                Cek Detail
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($apps->hasPages())
<nav class="pr-pagination mt-4" aria-label="Pagination">
    <ul>
        <li class="{{ $apps->onFirstPage() ? 'disabled' : '' }}">
            <a href="{{ $apps->previousPageUrl() ?? '#' }}">
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>
        @for($i = 1; $i <= $apps->lastPage(); $i++)
            <li class="{{ $apps->currentPage() === $i ? 'active' : '' }}">
                <a href="{{ $apps->url($i) }}">{{ $i }}</a>
            </li>
            @endfor
            <li class="{{ !$apps->hasMorePages() ? 'disabled' : '' }}">
                <a href="{{ $apps->nextPageUrl() ?? '#' }}">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
    </ul>
</nav>
@endif

@else
{{-- Empty state --}}
<div class="text-center py-5" style="border: 1px solid var(--pr-border); border-radius: 16px; background: #fff;">
    <div style="font-size: 3rem; color: var(--pr-orange-light);">
        <i class="bi bi-clipboard-check"></i>
    </div>
    <h6 class="fw-bold mt-3 mb-1">Belum ada permohonan adopsi</h6>
    <p style="color: var(--pr-text-muted); font-size: .9rem;" class="mb-3">
        Temukan hewan yang cocok dan mulai proses adopsimu.
    </p>
    <a href="{{ route('catalog.index') }}"
        class="btn pr-btn-primary"
        style="border-radius: 12px; padding: 10px 24px;">
        Lihat Katalog Hewan
    </a>
</div>
@endif

@endsection