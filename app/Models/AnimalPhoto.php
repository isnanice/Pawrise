<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model untuk data foto pendukung hewan
class AnimalPhoto extends Model
{
    protected $fillable = ['animal_id', 'photo_path', 'sort_order'];

    // Relasi ke model Animal (hewan pemilik foto)
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    // Mendapatkan URL lengkap untuk foto pendukung
    public function photoUrl(): string
    {
        if (!$this->photo_path) return '';

        if (str_starts_with($this->photo_path, 'http')) {
            return $this->photo_path;
        }
        if (str_starts_with($this->photo_path, 'attached_assets/')) {
            return asset($this->photo_path); // → public/attached_assets/...
        }
        return asset('storage/' . $this->photo_path); // → storage/...
    }
}
