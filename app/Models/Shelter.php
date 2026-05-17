<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// Model untuk data shelter/tempat penampungan
class Shelter extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id', 'shelter_name', 'city', 'logo', 'phone', 'description', 'is_verified'];

    // Relasi ke model User (pemilik shelter)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    // Relasi ke model Animal (hewan yang dikelola shelter)
    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }

    // Mendapatkan URL lengkap logo shelter
    public function logoUrl(): string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode(substr($this->shelter_name, 0, 2)) . '&background=1F2937&color=F08C2A&size=96&bold=true';
    }
}
