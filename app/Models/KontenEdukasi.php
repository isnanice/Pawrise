<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// Model untuk konten edukasi tentang hewan peliharaan
class KontenEdukasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'konten_edukasi';

    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'gambar',
        'kategori',
        'estimasi_baca',
        'is_published',
        'admin_id',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Menginisialisasi event boot pada model
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->judul);
            }
        });
    }

    // Menggunakan slug untuk pencarian rute
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Aksesor untuk mendapatkan URL lengkap gambar edukasi
    public function getGambarUrlAttribute(): string
    {
        if ($this->gambar) {
            if (str_starts_with($this->gambar, 'http')) {
                return $this->gambar;
            }
            return Storage::url($this->gambar);
        }
        return asset('images/default-article.jpg');
    }

    // Aksesor untuk mendapatkan label kategori edukasi
    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori) {
            'kesehatan'   => 'Kesehatan',
            'pelatihan'   => 'Pelatihan',
            'nutrisi'     => 'Nutrisi',
            'gaya_hidup'  => 'Gaya Hidup',
            default       => 'Lainnya',
        };
    }

    // Scope query untuk memfilter konten yang sudah diterbitkan
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope query untuk memfilter konten berdasarkan kategori
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
} 
