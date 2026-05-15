<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone',
        'profile_photo', 'address', 'bio',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isShelter(): bool
    {
        return $this->role === 'shelter';
    }

    public function isAdopter(): bool
    {
        return $this->role === 'adopter';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function shelter(): HasOne
    {
        return $this->hasOne(Shelter::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(AdoptionApplication::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteAnimals(): BelongsToMany
    {
        return $this->belongsToMany(Animal::class, 'favorites')->withTimestamps();
    }

    public function profilePhotoUrl(): string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        $initials = strtoupper(substr($this->name, 0, 2));
        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&background=F08C2A&color=fff&size=128';
    }
}
