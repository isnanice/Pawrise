@csrf

{{-- ============================================================
     SECTION 1 — INFORMASI DASAR
     ============================================================ --}}
<div class="pr-form-section mb-4">
    <div class="pr-form-section-title">
        <span class="pr-form-section-icon">
            <i class="bi bi-info-circle-fill" style="color: var(--pr-orange);"></i>
        </span>
        Informasi Dasar
    </div>

    <div class="row g-3 mt-1">
        {{-- Nama Hewan --}}
        <div class="col-md-8">
            <label class="pr-form-label">Nama Hewan</label>
            <input type="text" name="name"
                   value="{{ old('name', $animal->name ?? '') }}"
                   class="pr-form-input @error('name') is-invalid @enderror"
                   placeholder="Contoh: Milo" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Spesies --}}
        <div class="col-md-4">
            <label class="pr-form-label">Spesies</label>
            <div class="pr-form-select-wrap">
                <select name="species" class="pr-form-select @error('species') is-invalid @enderror" required>
                    @foreach(['anjing','kucing','lainnya'] as $sp)
                        <option value="{{ $sp }}" {{ old('species', $animal->species ?? '') == $sp ? 'selected' : '' }}>
                            {{ ucfirst($sp) }}
                        </option>
                    @endforeach
                </select>
                <i class="bi bi-chevron-down pr-select-arrow"></i>
            </div>
        </div>

        {{-- Ras / Breed --}}
        <div class="col-md-5">
            <label class="pr-form-label">Ras / Breed</label>
            <input type="text" name="breed"
                   value="{{ old('breed', $animal->breed ?? '') }}"
                   class="pr-form-input @error('breed') is-invalid @enderror"
                   placeholder="Contoh: Golden Retriever" required>
            @error('breed')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Umur --}}
        <div class="col-md-4">
            <label class="pr-form-label">Umur (Bulan/Tahun)</label>
            <input type="number" name="age_months"
                   value="{{ old('age_months', $animal->age_months ?? '') }}"
                   class="pr-form-input @error('age_months') is-invalid @enderror"
                   placeholder="Contoh: 24" min="0" required>
            @error('age_months')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Berat --}}
        <div class="col-md-3">
            <label class="pr-form-label">Berat (Kg)</label>
            <input type="number" step="0.1" name="weight_kg"
                   value="{{ old('weight_kg', $animal->weight_kg ?? '') }}"
                   class="pr-form-input @error('weight_kg') is-invalid @enderror"
                   placeholder="5" min="0">
            @error('weight_kg')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Jenis Kelamin --}}
        <div class="col-12">
            <label class="pr-form-label">Jenis Kelamin</label>
            <div class="pr-gender-group">
                <label class="pr-gender-btn {{ old('gender', $animal->gender ?? '') == 'jantan' || (!old('gender', null) && !isset($animal)) ? 'active' : '' }}">
                    <input type="radio" name="gender" value="jantan"
                           {{ old('gender', $animal->gender ?? 'jantan') == 'jantan' ? 'checked' : '' }}>
                    Jantan
                </label>
                <label class="pr-gender-btn {{ old('gender', $animal->gender ?? '') == 'betina' ? 'active' : '' }}">
                    <input type="radio" name="gender" value="betina"
                           {{ old('gender', $animal->gender ?? '') == 'betina' ? 'checked' : '' }}>
                    Betina
                </label>
            </div>
        </div>

        {{-- Sifat & Karakter --}}
        <div class="col-12">
            <label class="pr-form-label">Sifat & Karakter</label>
            <div class="pr-tags-wrap" id="tags-wrap">
                @php
                    $defaultTags = ['Ramah Anak','Aktif','Cerdas','Tenang','Penyayang','Pemalu','Energik'];
                    $selectedTags = old('characteristics', $animal->characteristics ?? '');
                    $selectedArr = array_filter(array_map('trim', explode(',', $selectedTags)));
                @endphp
                @foreach($defaultTags as $tag)
                    <label class="pr-tag {{ in_array($tag, $selectedArr) ? 'selected' : '' }}">
                        <input type="checkbox" class="pr-tag-cb" value="{{ $tag }}"
                               {{ in_array($tag, $selectedArr) ? 'checked' : '' }}>
                        {{ $tag }}
                    </label>
                @endforeach
                <button type="button" class="pr-tag-add" id="btn-add-tag">+ Tambah Sifat</button>
            </div>
            {{-- hidden input yang dikumpulkan dari checkbox --}}
            <input type="hidden" name="characteristics" id="characteristics-hidden"
                   value="{{ old('characteristics', $animal->characteristics ?? '') }}">
            <p class="pr-form-hint">Pilih beberapa sifat yang paling menggambarkan hewan ini.</p>
        </div>

        {{-- Ukuran & Status (tersembunyi tapi tetap dikirim) --}}
        <div class="col-md-4">
            <label class="pr-form-label">Ukuran</label>
            <div class="pr-form-select-wrap">
                <select name="size" class="pr-form-select" required>
                    @foreach(['kecil','sedang','besar'] as $sz)
                        <option value="{{ $sz }}" {{ old('size', $animal->size ?? '') == $sz ? 'selected' : '' }}>{{ ucfirst($sz) }}</option>
                    @endforeach
                </select>
                <i class="bi bi-chevron-down pr-select-arrow"></i>
            </div>
        </div>
        <div class="col-md-4">
            <label class="pr-form-label">Status</label>
            <div class="pr-form-select-wrap">
                <select name="status" class="pr-form-select" required>
                    @foreach(['tersedia','diproses','diadopsi'] as $st)
                        <option value="{{ $st }}" {{ old('status', $animal->status ?? 'tersedia') == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
                <i class="bi bi-chevron-down pr-select-arrow"></i>
            </div>
        </div>
        <div class="col-md-4">
            <label class="pr-form-label">Kode Hewan</label>
            <input type="text" name="code"
                   value="{{ old('code', $animal->code ?? '') }}"
                   class="pr-form-input"
                   placeholder="otomatis">
        </div>
    </div>
</div>

{{-- ============================================================
     SECTION 2 — MEDIA / UPLOAD FOTO
     ============================================================ --}}
<div class="pr-form-section mb-4">
    <div class="pr-form-section-title">
        <span class="pr-form-section-icon">
            <i class="bi bi-images" style="color: var(--pr-orange);"></i>
        </span>
        Media
    </div>

    <div class="pr-upload-zone mt-3" id="upload-zone" onclick="document.getElementById('main_photo_input').click()">
        <img id="preview-photo"
             src="{{ isset($animal) && $animal->main_photo ? asset($animal->main_photo) : '' }}"
             alt="preview" class="pr-upload-preview {{ isset($animal) && $animal->main_photo ? '' : 'd-none' }}"
             id="img-preview">
        <div id="upload-placeholder" class="{{ isset($animal) && $animal->main_photo ? 'd-none' : '' }}">
            <i class="bi bi-camera-fill pr-upload-icon"></i>
            <p class="pr-upload-text">Klik untuk Unggah Foto Utama</p>
            <p class="pr-upload-hint">Rekomendasi: 1200x800px</p>
        </div>
    </div>
    <input type="file" name="main_photo" id="main_photo_input" accept="image/*" class="d-none">
</div>

{{-- ============================================================
     SECTION 3 — CERITA & DESKRIPSI
     ============================================================ --}}
<div class="pr-form-section mb-4">
    <div class="pr-form-section-title">
        <span class="pr-form-section-icon">
            <i class="bi bi-file-text-fill" style="color: var(--pr-orange);"></i>
        </span>
        Cerita & Deskripsi
    </div>

    <div class="mt-3">
        <label class="pr-form-label">Bio & Kepribadian</label>
        <textarea name="description" rows="5" class="pr-form-textarea @error('description') is-invalid @enderror"
                  placeholder="Ceritakan tentang latar belakang, sifat unik, dan kebiasaan hewan ini agar calon adopter merasa terhubung...">{{ old('description', $animal->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <p class="pr-form-hint">Minimal 100 karakter untuk hasil terbaik.</p>
    </div>
</div>

{{-- ============================================================
     SECTION 4 — RIWAYAT MEDIS & KESEHATAN
     ============================================================ --}}
<div class="pr-form-section mb-4">
    <div class="pr-form-section-title">
        <span class="pr-form-section-icon">
            <i class="bi bi-heart-pulse-fill" style="color: var(--pr-orange);"></i>
        </span>
        Riwayat Medis &amp; Kesehatan
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <label class="pr-check-card">
                <input type="checkbox" name="vaccinated" value="1" class="pr-check-input"
                       id="vac" {{ old('vaccinated', $animal->vaccinated ?? false) ? 'checked' : '' }}>
                <div>
                    <div class="pr-check-title">Vaksinasi Rabies</div>
                    <div class="pr-check-desc">Terakhir dilakukan dalam 1 tahun terakhir.</div>
                </div>
            </label>
        </div>
        <div class="col-md-6">
            <label class="pr-check-card">
                <input type="checkbox" name="vaccinated_distemper" value="1" class="pr-check-input"
                       {{ old('vaccinated_distemper', false) ? 'checked' : '' }}>
                <div>
                    <div class="pr-check-title">Vaksinasi Distemper/Parvo</div>
                    <div class="pr-check-desc">Terakhir dilakukan dalam 1 tahun terakhir.</div>
                </div>
            </label>
        </div>
        <div class="col-md-6">
            <label class="pr-check-card">
                <input type="checkbox" name="sterilized" value="1" class="pr-check-input"
                       id="ster" {{ old('sterilized', $animal->sterilized ?? false) ? 'checked' : '' }}>
                <div>
                    <div class="pr-check-title">Sudah Disteril</div>
                    <div class="pr-check-desc">Telah melalui prosedur sterilisasi medis.</div>
                </div>
            </label>
        </div>
        <div class="col-md-6">
            <label class="pr-check-card">
                <input type="checkbox" name="dewormed" value="1" class="pr-check-input"
                       {{ old('dewormed', false) ? 'checked' : '' }}>
                <div>
                    <div class="pr-check-title">Pengobatan Cacing Rutin</div>
                    <div class="pr-check-desc">Mendapatkan obat cacing secara berkala.</div>
                </div>
            </label>
        </div>
    </div>
</div>

{{-- ============================================================
     SECTION 5 — STATUS KESEHATAN
     ============================================================ --}}
<div class="pr-form-section mb-2">
    <div class="pr-form-section-title">
        <span class="pr-form-section-icon">
            <i class="bi bi-shield-fill-check" style="color: var(--pr-orange);"></i>
        </span>
        Status Kesehatan
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <label class="pr-check-card">
                <input type="checkbox" class="pr-check-input" disabled
                       {{ old('vaccinated', $animal->vaccinated ?? false) ? 'checked' : '' }}>
                <div>
                    <div class="pr-check-title">Sudah Vaksinasi</div>
                    <div class="pr-check-desc">Memiliki catatan vaksin yang lengkap.</div>
                </div>
            </label>
        </div>
        <div class="col-md-6">
            <label class="pr-check-card">
                <input type="checkbox" class="pr-check-input" disabled
                       {{ old('sterilized', $animal->sterilized ?? false) ? 'checked' : '' }}>
                <div>
                    <div class="pr-check-title">Sudah Steril</div>
                    <div class="pr-check-desc">Telah melalui prosedur sterilisasi.</div>
                </div>
            </label>
        </div>
        <div class="col-md-6">
            <label class="pr-check-card">
                <input type="checkbox" name="flea_free" value="1" class="pr-check-input"
                       {{ old('flea_free', false) ? 'checked' : '' }}>
                <div>
                    <div class="pr-check-title">Bebas Kutu &amp; Cacing</div>
                    <div class="pr-check-desc">Rutin mendapatkan pengobatan antiparasit.</div>
                </div>
            </label>
        </div>
        <div class="col-md-6">
            <label class="pr-check-card">
                <input type="checkbox" name="special_needs" value="1" class="pr-check-input"
                       {{ old('special_needs', false) ? 'checked' : '' }}>
                <div>
                    <div class="pr-check-title">Kebutuhan Khusus</div>
                    <div class="pr-check-desc">Memerlukan perhatian medis tertentu.</div>
                </div>
            </label>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── Upload photo preview ──
const input = document.getElementById('main_photo_input');
const zone  = document.getElementById('upload-zone');
const preview = document.getElementById('preview-photo');
const placeholder = document.getElementById('upload-placeholder');

input.addEventListener('change', function () {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(this.files[0]);
    }
});

// Drag & drop
zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragging'); });
zone.addEventListener('dragleave', () => zone.classList.remove('dragging'));
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('dragging');
    if (e.dataTransfer.files[0]) {
        input.files = e.dataTransfer.files;
        input.dispatchEvent(new Event('change'));
    }
});

// ── Tags / Sifat karakter ──
function syncTags() {
    const checked = [...document.querySelectorAll('.pr-tag-cb:checked')].map(cb => cb.value);
    document.getElementById('characteristics-hidden').value = checked.join(', ');
}

document.querySelectorAll('.pr-tag').forEach(label => {
    label.addEventListener('click', () => {
        setTimeout(() => {
            label.classList.toggle('selected', label.querySelector('.pr-tag-cb').checked);
            syncTags();
        }, 0);
    });
});

// Tambah sifat custom
document.getElementById('btn-add-tag').addEventListener('click', () => {
    const val = prompt('Masukkan sifat baru:');
    if (!val || !val.trim()) return;
    const wrap = document.getElementById('tags-wrap');
    const btn  = document.getElementById('btn-add-tag');
    const label = document.createElement('label');
    label.className = 'pr-tag selected';
    label.innerHTML = `<input type="checkbox" class="pr-tag-cb" value="${val.trim()}" checked>${val.trim()}`;
    label.addEventListener('click', () => {
        setTimeout(() => {
            label.classList.toggle('selected', label.querySelector('.pr-tag-cb').checked);
            syncTags();
        }, 0);
    });
    wrap.insertBefore(label, btn);
    syncTags();
});

// ── Gender toggle visual ──
document.querySelectorAll('input[name="gender"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.pr-gender-btn').forEach(btn => {
            btn.classList.toggle('active', btn.querySelector('input').checked);
        });
    });
});

// init sync
syncTags();
</script>
@endpush