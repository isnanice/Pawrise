<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Model untuk data permohonan adopsi hewan
class AdoptionApplication extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'animal_id',
        'user_id',
        'full_name',
        'whatsapp',
        'email',
        'address',
        'reason',
        'experience',
        'agreement',
        'status',
        'shelter_note', // ← tambahkan, ada di migration
    ];

    protected $casts = [
        'agreement' => 'boolean', // ← tambahkan, agar cast benar
    ];

    // Relasi ke model Animal (hewan yang ingin diadopsi)
    public function animal()
    {
        return $this->belongsTo(Animal::class)->withTrashed();
    }

    // Relasi ke model User (pembuat permohonan)
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    // Mendapatkan label status permohonan dalam bahasa Indonesia
    public function statusLabel(): string
    {
        return match($this->status) {
            'menunggu'  => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            default     => ucfirst($this->status),
        };
    }

    // Mendapatkan label tingkat pengalaman pemeliharaan hewan
    public function experienceLabel(): string
    {
        return match($this->experience) {
            'belum'  => 'Belum Pernah',
            'pernah' => 'Pernah di Masa Lalu',
            'sedang' => 'Sedang Memelihara',
            default  => ucfirst($this->experience),
        };
    }
}
