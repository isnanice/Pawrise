@extends('layouts.guest')
@section('title', 'Profil Shelter: ' . $shelter->shelter_name . ' - PawRise')

@section('content')
<style>
.shelter-header {
    background: linear-gradient(135deg, var(--pr-orange-light) 0%, rgba(240,140,42,0.1) 100%);
    border-radius: 24px;
    padding: 40px;
    margin-bottom: 40px;
    position: relative;
    overflow: hidden;
}
.shelter-logo-container {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #fff;
    padding: 8px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    flex-shrink: 0;
}
.shelter-logo-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
.contact-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #fff;
    border-radius: 999px;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--pr-text);
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
}
</style>

<div class="container py-4" style="max-width: 1140px;">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: .88rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color:var(--pr-text-muted);">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}" class="text-decoration-none" style="color:var(--pr-text-muted);">Katalog</a></li>
            <li class="breadcrumb-item active" aria-current="page" style="color:var(--pr-orange); font-weight: 500;">Profil Shelter</li>
        </ol>
    </nav>

    {{-- Shelter Header --}}
    <div class="shelter-header d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4">
        <div class="shelter-logo-container">
            <img src="{{ $shelter->logoUrl() }}" alt="{{ $shelter->shelter_name }}">
        </div>
        <div class="flex-grow-1 text-center text-md-start">
            <h1 class="fw-bold mb-2">{{ $shelter->shelter_name }}</h1>
            <p style="color: var(--pr-text-muted); font-size: 1.05rem; margin-bottom: 20px;">
                <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $shelter->city ?? 'Indonesia' }}
            </p>
            <p style="color: var(--pr-text); line-height: 1.6; max-width: 800px; margin: 0 auto 24px auto; text-align: left;" class="mx-md-0">
                {{ $shelter->description ?? 'Shelter ini berdedikasi untuk menyelamatkan, merehabilitasi, dan merawat hewan terlantar. Kami berkomitmen untuk mencarikan rumah baru yang penuh kasih sayang untuk setiap hewan di bawah asuhan kami.' }}
            </p>

            <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start">
                @if($shelter->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $shelter->phone) }}" target="_blank" class="contact-badge text-decoration-none">
                        <i class="bi bi-whatsapp" style="color: #25D366;"></i> {{ $shelter->phone }}
                    </a>
                @endif
                <div class="contact-badge">
                    <i class="bi bi-envelope-fill text-primary"></i> {{ $shelter->user->email }}
                </div>
            </div>
        </div>
    </div>

    {{-- Animals List --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h3 class="fw-bold mb-1">Hewan dari Shelter Ini</h3>
            <p class="mb-0" style="color:var(--pr-text-muted); font-size: .95rem;">Mereka menunggu keluarga baru</p>
        </div>
        <div>
            <span class="badge" style="background: var(--pr-orange); padding: 8px 16px; font-size: .9rem; border-radius: 12px;">
                Total: {{ $animals->total() }}
            </span>
        </div>
    </div>

    @if($animals->count() > 0)
        <div class="row g-4 mb-5">
            @foreach($animals as $animal)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    @include('partials.animal-card', ['animal' => $animal])
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $animals->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5" style="background: var(--pr-bg); border-radius: 16px; border: 1px dashed var(--pr-border);">
            <div class="mb-3">
                <i class="bi bi-emoji-frown" style="font-size: 3rem; color: var(--pr-text-muted);"></i>
            </div>
            <h5 class="fw-bold">Belum ada hewan</h5>
            <p style="color: var(--pr-text-muted);">Shelter ini belum memiliki hewan yang tersedia untuk diadopsi saat ini.</p>
        </div>
    @endif

</div>
@endsection
