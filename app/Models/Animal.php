<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// Model untuk data hewan peliharaan
class Animal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'shelter_id', 'code', 'name', 'species', 'breed', 'age_months',
        'weight_kg', 'gender', 'size', 'vaccinated', 'sterilized', 'status',
        'description', 'characteristics', 'medical_history', 'main_photo',
    ];

    protected $casts = [
        'vaccinated' => 'boolean',
        'sterilized' => 'boolean',
        'weight_kg' => 'decimal:2',
    ];

    // Relasi ke model Shelter (tempat penampungan hewan)
    public function shelter(): BelongsTo
    {
        return $this->belongsTo(Shelter::class)->withTrashed();
    }

    // Relasi ke model AnimalPhoto (foto-foto pendukung hewan)
    public function photos(): HasMany
    {
        return $this->hasMany(AnimalPhoto::class)->orderBy('sort_order');
    }

    // Relasi ke model AdoptionApplication (permohonan adopsi)
    public function applications(): HasMany
    {
        return $this->hasMany(AdoptionApplication::class);
    }

    // Relasi ke model Favorite (hewan terfavorit)
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    // Mendapatkan format label umur hewan (tahun dan bulan)
    public function ageLabel(): string
    {
        if ($this->age_months < 6) return $this->age_months . ' Bln';
        if ($this->age_months < 12) return $this->age_months . ' Bln';
        $years = round($this->age_months / 12, 1);
        return ($years == intval($years) ? intval($years) : $years) . ' Thn';
    }

    // Mengelompokkan umur hewan (anak, muda, dewasa, senior)
    public function ageGroup(): string
    {
        if ($this->age_months < 6) return 'bayi';
        if ($this->age_months <= 12) return 'muda';
        if ($this->age_months <= 60) return 'dewasa';
        return 'senior';
    }

    // Mendapatkan label status ketersediaan hewan
    public function statusLabel(): string
    {
        return match($this->status) {
            'tersedia' => 'Tersedia',
            'diproses' => 'Diproses',
            'diadopsi' => 'Diadopsi',
            default => ucfirst($this->status),
        };
    }

    // Mendapatkan label spesies hewan (anjing, kucing, dll)
    public function speciesLabel(): string
    {
        return match($this->species) {
            'anjing' => 'Anjing',
            'kucing' => 'Kucing',
            default => 'Lainnya',
        };
    }

    // Mendapatkan URL foto utama hewan
    public function mainPhotoUrl(): string
    {
        if ($this->main_photo) {
            if (str_starts_with($this->main_photo, 'http')) return $this->main_photo;
            // Photos in public/attached_assets use asset(), storage/ paths use asset('storage/...')
            if (str_starts_with($this->main_photo, 'attached_assets/')) {
                return asset($this->main_photo);
            }
            return asset('storage/' . $this->main_photo);
        }
        return 'https://placehold.co/600x450/F08C2A/fff?text=' . urlencode($this->name);
    }

    // Mengubah string karakteristik menjadi array
    public function characteristicsArray(): array
    {
        if (!$this->characteristics) return [];
        return array_filter(array_map('trim', explode(',', $this->characteristics)));
    }
}
