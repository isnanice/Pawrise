<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    // ── Boot ───────────────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->judul);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // ── Accessors ──────────────────────────────────────────────
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

    // ── Scopes ─────────────────────────────────────────────────
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
} 