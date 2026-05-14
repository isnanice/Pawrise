@extends('layouts.guest')
@section('title', $shelter->shelter_name . ' - PawRise')
@section('content')

<div class="container py-5" style="max-width: 1080px;">

    {{-- Header profil shelter --}}
    <div class="row g-4 align-items-center mb-5">
        <div class="col-auto">
            <img src="{{ $shelter->logoUrl() }}" alt="{{ $shelter->shelter_name }}"
                 style="width:90px; height:90px; border-radius:50%; object-fit:cover;
                        border: 3px solid var(--pr-orange);">
        </div>
        <div class="col">
            <div class="d-flex align-items-center gap-2 mb-1">
                <h2 class="fw-bold mb-0">{{ $shelter->shelter_name }}</h2>
                @if($shelter->is_verified)
                    <span style="background:#D1FAE5; color:#065F46; border-radius:999px;
                                 font-size:.75rem; font-weight:700; padding:3px 10px;">
                        ✓ Terverifikasi
                    </span>
                @endif
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap" style="color:var(--pr-text-muted); font-size:.9rem;">
                <span><i class="bi bi-geo-alt"></i> {{ $shelter->city ?? 'Indonesia' }}</span>
                @if($shelter->phone)
                    <span><i class="bi bi-telephone"></i> {{ $shelter->phone }}</span>
                @endif
                <span><i class="bi bi-paw-fill" style="color:var(--pr-orange);"></i> {{ $shelter->animals()->count() }} hewan terdaftar</span>
            </div>
        </div>
        @if($shelter->phone)
        @php
            $phone = preg_replace('/[^0-9]/', '', $shelter->phone);
            $phone = str_starts_with($phone, '0') ? '62' . substr($phone, 1) : $phone;
        @endphp
        <div class="col-auto">
            <a href="https://wa.me/{{ $phone }}?text={{ urlencode('Halo, saya ingin bertanya tentang shelter ' . $shelter->shelter_name) }}"
               target="_blank"
               class="btn pr-btn-primary d-inline-flex align-items-center gap-2"
               style="border-radius: 12px; padding: 10px 20px;">
                <i class="bi bi-whatsapp"></i> Hubungi Shelter
            </a>
        </div>
        @endif
    </div>

    {{-- Deskripsi --}}
    @if($shelter->description)
    <div class="pr-card p-4 mb-5">
        <h5 class="fw-bold mb-2">Tentang Shelter</h5>
        <p style="color:var(--pr-text-muted); line-height:1.8; margin-bottom:0;">
            {{ $shelter->description }}
        </p>
    </div>
    @endif

    {{-- Hewan tersedia --}}
    <h4 class="fw-bold mb-4">Hewan Tersedia untuk Adopsi</h4>

    @if($animals->count())
        <div class="row g-3">
            @foreach($animals as $animal)
                <div class="col-sm-6 col-md-4">
                    @include('partials.animal-card', ['animal' => $animal])
                </div>
            @endforeach
        </div>

        @if($animals->hasPages())
            <nav class="pr-pagination mt-4">
                <ul>
                    <li class="{{ $animals->onFirstPage() ? 'disabled' : '' }}">
                        <a href="{{ $animals->previousPageUrl() ?? '#' }}"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    @for($i = 1; $i <= $animals->lastPage(); $i++)
                        <li class="{{ $animals->currentPage() === $i ? 'active' : '' }}">
                            <a href="{{ $animals->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="{{ !$animals->hasMorePages() ? 'disabled' : '' }}">
                        <a href="{{ $animals->nextPageUrl() ?? '#' }}"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        @endif
    @else
        <div class="text-center py-5 pr-card">
            <i class="bi bi-search" style="font-size:2.5rem; color:var(--pr-orange-light);"></i>
            <p class="mt-3 mb-0" style="color:var(--pr-text-muted);">Belum ada hewan tersedia saat ini.</p>
        </div>
    @endif

</div>

@endsection