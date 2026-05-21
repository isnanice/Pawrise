@extends('layouts.guest')
@section('title', $animal->name . ' - PawRise')
@section('content')

<style>
    .detail-photo-main {
        width: 100%;
        aspect-ratio: 1 / 1;
        object-fit: cover;
        border-radius: 18px;
        display: block;
        cursor: zoom-in;
    }

    .detail-thumb {
        width: 88px;
        height: 66px;
        object-fit: cover;
        border-radius: 10px;
        cursor: pointer;
        border: 2.5px solid transparent;
        transition: border-color .15s;
        display: block;
    }

    .detail-thumb.active,
    .detail-thumb:hover {
        border-color: var(--pr-orange);
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 14px;
        background: var(--pr-bg);
        border: 1px solid var(--pr-border);
        height: 100%;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .stat-label {
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .07em;
        font-weight: 700;
        color: var(--pr-text-muted);
        margin-bottom: 2px;
    }

    .stat-value {
        font-weight: 700;
        font-size: .95rem;
        color: var(--pr-text);
        line-height: 1.2;
    }

    .cta-box {
        border: 1px solid var(--pr-border);
        border-radius: 16px;
        padding: 20px;
        background: #fff;
    }

    .shelter-card {
        border: 1px solid var(--pr-border);
        border-radius: 16px;
        padding: 20px;
        background: #fff;
    }

    .trait-tag {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 999px;
        background: var(--pr-orange-light);
        color: var(--pr-orange-dark);
        font-size: .85rem;
        font-weight: 500;
    }

    .medis-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 6px 0;
        font-size: .95rem;
    }
</style>

<div class="container py-4" style="max-width: 1080px;">

    {{-- Back --}}
    <a href="{{ route('catalog.index') }}"
        class="d-inline-flex align-items-center gap-2 mb-4 text-decoration-none fw-semibold"
        style="color: var(--pr-text-muted); font-size: .88rem;">
        <i class="bi bi-arrow-left"></i> Kembali ke Katalog
    </a>

    {{-- ====== SATU ROW: Kiri (foto+tentang+medis) | Kanan (info+cta+shelter) ====== --}}
    <div class="row g-4 align-items-start mb-5">

        {{-- ========== KOLOM KIRI ========== --}}
        <div class="col-lg-5">

            {{-- Foto utama --}}
            <img id="mainPhoto"
                src="{{ $animal->mainPhotoUrl() }}"
                alt="{{ $animal->name }}"
                class="detail-photo-main mb-3"
                onclick="openLightbox(this.src)">

            {{-- Thumbnails Dihilangkan sesuai permintaan --}}

            {{-- Tentang --}}
            <h3 class="fw-bold mb-3">Tentang {{ $animal->name }}</h3>
            <p style="color:var(--pr-text-muted);line-height:1.8;font-size:.96rem;">
                {{ $animal->description ?? 'Belum ada deskripsi.' }}
            </p>

            {{-- Riwayat Medis --}}
            @php
            $medisList = [];
            if (!empty($animal->vaccinated)) {
                $tgl = $animal->vaccinated_rabies_date ? $animal->vaccinated_rabies_date->translatedFormat('j F Y') : null;
                $medisList[] = ['label' => 'Vaksin Rabies', 'note' => $tgl];
            }
            if (!empty($animal->vaccinated_distemper)) {
                $tgl2 = $animal->vaccinated_distemper_date ? $animal->vaccinated_distemper_date->translatedFormat('j F Y') : null;
                $medisList[] = ['label' => 'Vaksinasi Distemper/Parvo', 'note' => $tgl2];
            }
            if (!empty($animal->sterilized)) $medisList[] = ['label' => 'Sudah disteril', 'note' => null];
            if (!empty($animal->dewormed)) $medisList[] = ['label' => 'Pengobatan cacing rutin', 'note' => null];
            if (!empty($animal->flea_free)) $medisList[] = ['label' => 'Bebas Kutu & Cacing', 'note' => null];
            if (!empty($animal->special_needs)) $medisList[] = ['label' => 'Berkebutuhan Khusus', 'note' => null];

            // fallback: jika ada medical_history teks lama
            $extraMedis = [];
            if (!empty($animal->medical_history)) {
                $extraMedis = array_filter(array_map('trim', explode("\n", $animal->medical_history)));
            }
            @endphp
            @if(count($medisList) || count($extraMedis))
            <h5 class="fw-bold mt-4 mb-3">Riwayat Medis</h5>
            @foreach($medisList as $m)
            <div class="medis-item">
                <i class="bi bi-check-circle-fill mt-1 flex-shrink-0" style="color:var(--pr-success);font-size:1.05rem;"></i>
                <span>{{ $m['label'] }}@if($m['note']) <span style="color:var(--pr-text-muted);font-size:.88rem;">({{ $m['note'] }})</span>@endif</span>
            </div>
            @endforeach
            @foreach($extraMedis as $item)
            <div class="medis-item">
                <i class="bi bi-check-circle-fill mt-1 flex-shrink-0" style="color:var(--pr-success);font-size:1.05rem;"></i>
                <span>{{ $item }}</span>
            </div>
            @endforeach
            @endif

        </div>
        {{-- end kolom kiri --}}

        {{-- ========== KOLOM KANAN ========== --}}
        <div class="col-lg-7">

            {{-- Badge + lokasi --}}
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span style="background:#D1FAE5;color:#065F46;border-radius:999px;
                             font-size:.78rem;font-weight:700;padding:4px 12px;">
                    {{ $animal->speciesLabel() }}
                </span>
                <span style="color:var(--pr-text-muted);font-size:.87rem;">
                    <i class="bi bi-geo-alt"></i>
                    {{ $animal->shelter->shelter_name ?? '—' }}@if($animal->shelter?->city), {{ $animal->shelter->city }}@endif
                </span>
            </div>

            <h1 class="fw-bold mb-0" style="font-size:2.2rem;line-height:1.1;">{{ $animal->name }}</h1>
            <p class="mb-3" style="color:var(--pr-text-muted);font-size:1rem;margin-top:4px;">{{ $animal->breed }}</p>

            {{-- Stats 2x2 --}}
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#E0F2FE;">
                            <i class="bi bi-calendar3" style="color:#0369A1;"></i>
                        </div>
                        <div>
                            <div class="stat-label">Umur</div>
                            <div class="stat-value">{{ $animal->ageLabel() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#FEF3C7;">
                            <i class="bi bi-arrows-expand-vertical" style="color:#B45309;"></i>
                        </div>
                        <div>
                            <div class="stat-label">Berat</div>
                            <div class="stat-value">{{ $animal->weight_kg ?? '—' }} kg</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#FCE7F3;">
                            <i class="bi bi-gender-{{ $animal->gender === 'betina' ? 'female' : 'male' }}"
                                style="color:#9D174D;"></i>
                        </div>
                        <div>
                            <div class="stat-label">Gender</div>
                            <div class="stat-value">{{ ucfirst($animal->gender) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#D1FAE5;">
                            <i class="bi bi-shield-check" style="color:#065F46;"></i>
                        </div>
                        <div>
                            <div class="stat-label">Vaksinasi</div>
                            <div class="stat-value"
                                style="color:{{ $animal->vaccinated ? 'var(--pr-success)' : 'var(--pr-text-muted)' }};">
                                {{ $animal->vaccinated ? 'Lengkap' : 'Belum' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sifat & Karakter --}}
            @if($animal->characteristics)
            <h6 class="fw-bold mb-2" style="font-size:.95rem;">Sifat & Karakter</h6>
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach($animal->characteristicsArray() as $trait)
                <span class="trait-tag">{{ $trait }}</span>
                @endforeach
            </div>
            @endif

            {{-- CTA Box --}}
            <div class="cta-box mb-3">
                <p class="text-center mb-3" style="color:var(--pr-text-muted);font-size:.88rem;">
                    Siap memberikan rumah yang hangat untuk {{ $animal->name }}?
                </p>

                @if($animal->status === 'tersedia')
                @auth
                @if(auth()->user()->isAdopter())
                <a href="{{ route('adoption.create', $animal) }}"
                    class="btn pr-btn-primary w-100 mb-2 d-flex align-items-center justify-content-center gap-2"
                    style="border-radius:12px;padding:13px;font-size:.97rem;">
                    <i class="bi bi-heart-fill"></i> Ajukan Adopsi
                </a>
                @else
                <button class="btn w-100 mb-2" disabled
                    style="border-radius:12px;padding:13px;background:#f3f4f6;color:#9ca3af;font-weight:600;">
                    Login sebagai adopter untuk mengadopsi
                </button>
                @endif
                @else
                <a href="{{ route('login') }}"
                    class="btn pr-btn-primary w-100 mb-2 d-flex align-items-center justify-content-center gap-2"
                    style="border-radius:12px;padding:13px;font-size:.97rem;">
                    <i class="bi bi-heart-fill"></i> Masuk untuk Mengadopsi
                </a>
                @endauth
                @else
                <button class="btn w-100 mb-2" disabled
                    style="border-radius:12px;padding:13px;background:#f3f4f6;color:#9ca3af;font-weight:600;">
                    Tidak Tersedia
                </button>
                @endif

                <div class="d-flex gap-2">
                    @php $wa = $animal->shelter?->whatsapp ? preg_replace('/[^0-9]/', '', $animal->shelter->whatsapp) : null; @endphp
                    @if($wa)
                    <a href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo, saya ingin bertanya tentang ' . $animal->name) }}"
                        target="_blank"
                        class="btn flex-grow-1 fw-semibold"
                        style="border:1.5px solid var(--pr-orange);color:var(--pr-orange);border-radius:12px;padding:10px;">
                        Tanya Shelter
                    </a>
                    @else
                    @php
                    $phone = $animal->shelter?->phone;
                    $phone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
                    $phone = $phone && str_starts_with($phone, '0') ? '62' . substr($phone, 1) : $phone;
                    $waMsg = urlencode('Halo, saya ingin bertanya tentang ' . $animal->name . ' yang ada di ' . ($animal->shelter->shelter_name ?? 'shelter Anda'));
                    @endphp
                    <a href="{{ $phone ? 'https://wa.me/' . $phone . '?text=' . $waMsg : '#' }}"
                        target="_blank"
                        class="btn flex-grow-1 fw-semibold"
                        style="border:1.5px solid var(--pr-orange);color:var(--pr-orange);border-radius:12px;padding:10px;">
                        Tanya Shelter
                    </a>
                    @endif
                    <button onclick="shareAnimal()"
                        class="btn"
                        style="border:1.5px solid var(--pr-border);border-radius:12px;padding:10px 14px;color:var(--pr-text-muted);"
                        title="Bagikan">
                        <i class="bi bi-share"></i>
                    </button>
                </div>
            </div>

            {{-- Shelter Card --}}
            <div class="shelter-card">
                <h6 class="fw-bold mb-3">Shelter Terkait</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:50px;height:50px;border-radius:50%;background:var(--pr-orange-light);
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-house-heart-fill" style="color:var(--pr-orange);font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <div class="fw-bold">{{ $animal->shelter->shelter_name ?? '—' }}</div>
                        <div style="font-size:.82rem;color:var(--pr-text-muted);">{{ $animal->shelter->city ?? '' }}</div>
                    </div>
                </div>
                <p style="font-size:.86rem;color:var(--pr-text-muted);line-height:1.65;margin-bottom:14px;">
                    {{ $animal->shelter->description ?? 'Shelter ini berdedikasi untuk menyelamatkan dan merawat hewan terlantar di area ' . ($animal->shelter->city ?? 'Indonesia') . '.' }}
                </p>
                <a href="#" class="text-decoration-none fw-semibold" style="color:var(--pr-orange);font-size:.88rem;">
                    <a href="{{ route('shelter.profile', $animal->shelter) }}"
                        class="text-decoration-none fw-semibold" style="color:var(--pr-orange);font-size:.88rem;">
                        Lihat Profil Shelter →
                    </a>
                </a>
            </div>

        </div>
        {{-- end kolom kanan --}}

    </div>
    {{-- end row --}}

    {{-- ====== Hewan Serupa ====== --}}
    @if($similar->count())
    <h3 class="fw-bold mb-4">Hewan Serupa</h3>
    <div class="row g-3">
        @foreach($similar as $a)
        <div class="col-6 col-md-3">
            @include('partials.animal-card', ['animal' => $a])
        </div>
        @endforeach
    </div>
    @endif

</div>

{{-- Lightbox --}}
<div id="lightbox" onclick="closeLightbox()"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.88);
            z-index:9999;align-items:center;justify-content:center;cursor:zoom-out;">
    <img id="lightboxImg" src="" alt=""
        style="max-width:90vw;max-height:90vh;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,.5);">
</div>

<script>
    function switchPhoto(el) {
        document.getElementById('mainPhoto').src = el.src;
        document.querySelectorAll('.detail-thumb').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    function openLightbox(src) {
        document.getElementById('lightboxImg').src = src;
        document.getElementById('lightbox').style.display = 'flex';
    }

    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeLightbox();
    });

    function shareAnimal() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $animal->name }} - PawRise',
                url: window.location.href
            });
        } else {
            navigator.clipboard.writeText(window.location.href).then(() => alert('Link disalin!'));
        }
    }
</script>

@endsection