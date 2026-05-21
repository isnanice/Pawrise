@extends('layouts.app')
 
@section('title', 'Formulir Adopsi - PawRise')
 
@section('body')
 
{{-- Top bar dengan tombol Batal --}}
<div class="d-flex justify-content-end align-items-center px-4 py-3 bg-white border-bottom">
    <a href="{{ route('animals.show', $animal) }}" class="d-inline-flex align-items-center gap-2 text-decoration-none fw-semibold"
       style="color: var(--pr-text-muted); font-size: .9rem;">
        <i class="bi bi-x"></i> BATAL
    </a>
</div>
 
<div class="container-xl py-4" style="max-width: 1100px;">
    <div class="row g-4">
 
        {{-- ============ SIDEBAR KIRI ============ --}}
        <div class="col-lg-4">
 
            {{-- Pet card --}}
            <div class="pr-card overflow-hidden mb-3" style="border: 2px solid var(--pr-orange);">
                <div style="position: relative; aspect-ratio: 4/3; overflow: hidden; background: #eee;">
                    <img src="{{ $animal->mainPhotoUrl() }}"
                         alt="{{ $animal->name }}"
                         style="width:100%; height:100%; object-fit:cover;">
                    <span class="pr-tag"
                          style="position:absolute; bottom:12px; left:12px; border-radius:999px;">
                        <i class="bi bi-paw-fill me-1"></i> SIAP ADOPSI
                    </span>
                </div>
                <div class="p-3">
                    <h5 class="fw-bold mb-1">{{ $animal->name }}</h5>
                    <p class="mb-0" style="color: var(--pr-text-muted); font-size: .9rem;">
                        <i class="bi bi-geo-alt"></i> {{ $animal->shelter->shelter_name ?? '' }}
                    </p>
                </div>
            </div>
 
            {{-- Tahapan aplikasi --}}
            <div class="pr-card p-3">
                <p class="fw-bold mb-3" style="font-size: .95rem;">Tahapan Aplikasi</p>
 
                {{-- Step 1 --}}
                <div class="pr-step active">
                    <div class="pr-step-icon">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem;">1. Data Diri</div>
                        <div class="small-muted">Informasi kontak dasar</div>
                    </div>
                </div>
                {{-- Connector --}}
                <div style="width:2px; height:18px; background:var(--pr-border); margin-left:13px;"></div>
 
                {{-- Step 2 --}}
                <div class="pr-step">
                    <div class="pr-step-icon">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem; color: var(--pr-text-muted);">2. Alasan Adopsi</div>
                        <div class="small-muted">Motivasi merawat</div>
                    </div>
                </div>
                {{-- Connector --}}
                <div style="width:2px; height:18px; background:var(--pr-border); margin-left:13px;"></div>
 
                {{-- Step 3 --}}
                <div class="pr-step">
                    <div class="pr-step-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem; color: var(--pr-text-muted);">3. Pengalaman</div>
                        <div class="small-muted">Riwayat memelihara</div>
                    </div>
                </div>
            </div>
        </div>
 
        {{-- ============ FORM KANAN ============ --}}
        <div class="col-lg-8">
            <div class="pr-card p-4 p-md-5">
 
                {{-- Header --}}
                <h3 class="fw-bold mb-1">Formulir Permohonan Adopsi</h3>
                <p class="mb-4" style="color: var(--pr-text-muted);">
                    Lengkapi data di bawah ini untuk memulai perjalanan indah bersama sahabat barumu.<br>
                    Kami akan meninjau aplikasimu dengan penuh perhatian.
                </p>
 
                {{-- Error alert --}}
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
 
                @if($draft)
                <div class="alert mb-4 d-flex align-items-center gap-2"
                     style="background:#FFF7ED; border:1px solid #FED7AA; border-radius:12px; color:#92400E; font-size:.88rem;">
                    <i class="bi bi-floppy-fill" style="color:var(--pr-orange);"></i>
                    <span>Anda memiliki <strong>draft tersimpan</strong> untuk hewan ini. Data di bawah sudah diisi dari draft tersebut.</span>
                </div>
                @endif

                <form method="POST" action="{{ route('adoption.store', $animal) }}" id="adoptionForm">
                    @csrf
 
                    {{-- ---- Bagian 1: Data Pribadi ---- --}}
                    <div class="d-flex align-items-center gap-2 mb-3 pb-2"
                         style="border-bottom: 1px solid var(--pr-border);">
                        <span style="color: var(--pr-orange); font-size: 1.1rem;">
                            <i class="bi bi-person-fill"></i>
                        </span>
                        <h5 class="fw-bold mb-0" style="color: var(--pr-orange);">Data Pribadi</h5>
                    </div>
 
                    <div class="row g-3 mb-4">
                        {{-- Nama Lengkap --}}
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.06em; color:var(--pr-text-muted); font-weight:700;">
                                NAMA LENGKAP
                            </label>
                            <input type="text"
                                   name="full_name"
                                   value="{{ old('full_name', $draft->full_name ?? auth()->user()->name) }}"
                                   placeholder="Masukkan nama sesuai KTP"
                                   class="form-control @error('full_name') is-invalid @enderror"
                                   required>
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
 
                        {{-- No. WhatsApp --}}
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.06em; color:var(--pr-text-muted); font-weight:700;">
                                NOMOR WHATSAPP AKTIF
                            </label>
                            <input type="text"
                                   name="whatsapp"
                                   value="{{ old('whatsapp', $draft->whatsapp ?? auth()->user()->phone ?? '') }}"
                                   placeholder="Contoh: 08123456789"
                                   class="form-control @error('whatsapp') is-invalid @enderror"
                                   required>
                            @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
 
                        {{-- Email --}}
                        <div class="col-12">
                            <label class="form-label" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.06em; color:var(--pr-text-muted); font-weight:700;">
                                ALAMAT EMAIL
                            </label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $draft->email ?? auth()->user()->email) }}"
                                   placeholder="budi@example.com"
                                   class="form-control @error('email') is-invalid @enderror"
                                   required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
 
                        {{-- Alamat --}}
                        <div class="col-12">
                            <label class="form-label" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.06em; color:var(--pr-text-muted); font-weight:700;">
                                ALAMAT DOMISILI LENGKAP
                            </label>
                            <textarea name="address"
                                      rows="3"
                                      placeholder="Masukkan alamat lengkap beserta RT/RW dan kodepos"
                                      class="form-control @error('address') is-invalid @enderror"
                                      required>{{ old('address', $draft->address ?? auth()->user()->address ?? '') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
 
                    {{-- ---- Bagian 2: Alasan Adopsi ---- --}}
                    <div class="d-flex align-items-center gap-2 mb-3 pb-2"
                         style="border-bottom: 1px solid var(--pr-border);">
                        <span style="color: var(--pr-orange); font-size: 1.1rem;">
                            <i class="bi bi-heart-fill"></i>
                        </span>
                        <h5 class="fw-bold mb-0" style="color: var(--pr-orange);">Alasan Adopsi</h5>
                    </div>
 
                    <div class="mb-4">
                        <label class="form-label" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.06em; color:var(--pr-text-muted); font-weight:700;">
                            MENGAPA ANDA INGIN MENGADOPSI HEWAN INI?
                        </label>
                        <p class="mb-2" style="font-size:.85rem; color:var(--pr-text-muted);">
                            Ceritakan sedikit tentang motivasi Anda dan lingkungan rumah yang akan menjadi tempat tinggal barunya.
                        </p>
                        <textarea name="reason"
                                  rows="5"
                                  placeholder="Saya ingin mengadopsi karena..."
                                  class="form-control @error('reason') is-invalid @enderror"
                                  required>{{ old('reason', $draft->reason ?? '') }}</textarea>
                        @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
 
                    {{-- ---- Bagian 3: Pengalaman Memelihara ---- --}}
                    <div class="d-flex align-items-center gap-2 mb-3 pb-2"
                         style="border-bottom: 1px solid var(--pr-border);">
                        <span style="color: var(--pr-orange); font-size: 1.1rem;">
                            <i class="bi bi-clock-history"></i>
                        </span>
                        <h5 class="fw-bold mb-0" style="color: var(--pr-orange);">Pengalaman Memelihara</h5>
                    </div>
 
                    <div class="mb-4">
                        <label class="form-label mb-3" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.06em; color:var(--pr-text-muted); font-weight:700;">
                            APAKAH ANDA PERNAH ATAU SEDANG MEMELIHARA HEWAN PELIHARAAN?
                        </label>
 
                        {{-- Experience toggle buttons --}}
                        <div class="d-flex gap-2 flex-wrap" id="experienceGroup">
                            @foreach(['belum' => 'Belum Pernah', 'pernah' => 'Pernah di Masa Lalu', 'sedang' => 'Sedang Memelihara'] as $value => $label)
                                <button type="button"
                                        class="exp-btn btn @if(old('experience') === $value) exp-active @endif"
                                        data-value="{{ $value }}"
                                        style="border: 1.5px solid var(--pr-border); border-radius: 10px; padding: 10px 20px; font-weight: 600; font-size: .9rem; background: #fff; color: var(--pr-text); transition: all .15s;">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="experience" id="experienceInput" value="{{ old('experience', $draft->experience ?? '') }}" required>
                        @error('experience')<div class="text-danger mt-1" style="font-size:.85rem;">{{ $message }}</div>@enderror
                    </div>
 
                    {{-- Info box --}}
                    <div class="d-flex gap-3 p-3 mb-4 rounded-3"
                         style="background: var(--pr-info-bg); border: 1px solid #bfdbfe;">
                        <span style="color: var(--pr-info); font-size: 1.1rem; flex-shrink:0; margin-top:1px;">
                            <i class="bi bi-info-circle-fill"></i>
                        </span>
                        <p class="mb-0" style="font-size:.88rem; color: #1e40af; line-height:1.55;">
                            Tim kami mungkin akan menghubungi Anda untuk verifikasi data dan wawancara singkat
                            via telepon atau WhatsApp setelah formulir ini diajukan.
                        </p>
                    </div>
 
                    {{-- Agreement checkbox --}}
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <input class="form-check-input mt-1 flex-shrink-0 @error('agreement') is-invalid @enderror"
                               type="checkbox"
                               name="agreement"
                               value="1"
                               id="agreement"
                               {{ old('agreement') ? 'checked' : '' }}
                               required
                               style="width:18px; height:18px; accent-color: var(--pr-orange); cursor:pointer;">
                        <label for="agreement" class="mb-0" style="font-size:.9rem; color: var(--pr-text-muted); cursor:pointer; line-height:1.55;">
                            Saya menyatakan bahwa data yang diisi adalah benar. Saya bersedia untuk dihubungi oleh
                            pihak shelter dan memahami bahwa pengisian formulir ini tidak menjamin persetujuan adopsi.
                        </label>
                    </div>
                    @error('agreement')
                        <div class="text-danger mb-3" style="font-size:.85rem;">{{ $message }}</div>
                    @enderror
 
                    {{-- Action buttons --}}
                    <div class="d-flex align-items-center justify-content-end gap-3 pt-2">

                        <button type="button" id="btnSimpanDraft"
                                class="btn btn-link p-0 text-decoration-none fw-semibold"
                                style="color: var(--pr-text-muted); font-size:.95rem;">
                            <i class="bi bi-floppy me-1"></i> Simpan Draft
                        </button>

                        <button type="submit" class="pr-btn-primary d-inline-flex align-items-center gap-2 px-4 py-3"
                                style="border-radius: 14px; font-size:.95rem;">
                            Ajukan Permohonan <i class="bi bi-send-fill"></i>
                        </button>
                    </div>

                </form>

                {{-- Form draft di LUAR adoptionForm agar tidak nested --}}
                <form method="POST" action="{{ route('adoption.draft', $animal) }}" id="draftForm" style="display:none;">
                    @csrf
                    <input type="hidden" name="full_name"  id="draft_full_name">
                    <input type="hidden" name="whatsapp"   id="draft_whatsapp">
                    <input type="hidden" name="email"      id="draft_email">
                    <input type="hidden" name="address"    id="draft_address">
                    <input type="hidden" name="reason"     id="draft_reason">
                    <input type="hidden" name="experience" id="draft_experience">
                </form>
            </div>
        </div>
        {{-- end col-8 --}}
 
    </div>
</div>
 
{{-- Script untuk toggle experience --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btns = document.querySelectorAll('.exp-btn');
    const input = document.getElementById('experienceInput');
 
    const activeStyle = {
        background: 'var(--pr-orange)',
        color: '#fff',
        borderColor: 'var(--pr-orange)',
    };
    const inactiveStyle = {
        background: '#fff',
        color: 'var(--pr-text, #1F2A37)',
        borderColor: 'var(--pr-border, #E5E7EB)',
    };
 
    // Init: highlight if old value is set
    if (input.value) {
        btns.forEach(btn => {
            const isActive = btn.dataset.value === input.value;
            Object.assign(btn.style, isActive ? activeStyle : inactiveStyle);
        });
    }
 
    btns.forEach(btn => {
        btn.addEventListener('click', function () {
            input.value = this.dataset.value;
            btns.forEach(b => Object.assign(b.style, inactiveStyle));
            Object.assign(this.style, activeStyle);
        });
    });

    // Tombol Simpan Draft: salin data form ke draftForm lalu submit
    document.getElementById('btnSimpanDraft').addEventListener('click', function () {
        document.getElementById('draft_full_name').value  = document.querySelector('#adoptionForm [name="full_name"]').value;
        document.getElementById('draft_whatsapp').value   = document.querySelector('#adoptionForm [name="whatsapp"]').value;
        document.getElementById('draft_email').value      = document.querySelector('#adoptionForm [name="email"]').value;
        document.getElementById('draft_address').value    = document.querySelector('#adoptionForm [name="address"]').value;
        document.getElementById('draft_reason').value     = document.querySelector('#adoptionForm [name="reason"]').value;
        document.getElementById('draft_experience').value = document.getElementById('experienceInput').value;
        document.getElementById('draftForm').submit();
    });
});
</script>

@endsection