<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Model untuk data hewan favorit pengguna
class Favorite extends Model
{
    protected $fillable = ['user_id', 'animal_id'];

    // Relasi ke model User (pengguna yang memfavoritkan)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke model Animal (hewan yang difavoritkan)
    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }
}
