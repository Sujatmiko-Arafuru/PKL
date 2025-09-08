<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangans';

    protected $fillable = [
        'nama',
        'deskripsi',
        'foto1',
        'foto2',
        'foto3',
        'status',
        'kode',
        'kategori',
        'lantai',
        'lokasi',
        'fasilitas'
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function peminjamanDetails(): HasMany
    {
        return $this->hasMany(DetailPeminjamanRuangan::class);
    }

    /**
     * Scope: hanya ruangan yang benar-benar tersedia (status tersedia dan tidak sedang dipinjam)
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'tersedia')
            ->whereDoesntHave('peminjamanDetails', function ($q) {
                $q->whereHas('peminjaman', function ($sub) {
                    $sub->whereIn('status', ['disetujui', 'dipinjam']);
                });
            });
    }

    /**
     * Check if room is currently being borrowed
     */
    public function isCurrentlyBorrowed()
    {
        return $this->peminjamanDetails()
            ->whereHas('peminjaman', function ($query) {
                $query->whereIn('status', ['disetujui', 'dipinjam']);
            })
            ->exists();
    }

    /**
     * Get current active borrowing
     */
    public function getCurrentBorrowing()
    {
        return $this->peminjamanDetails()
            ->whereHas('peminjaman', function ($query) {
                $query->whereIn('status', ['disetujui', 'dipinjam']);
            })
            ->with('peminjaman')
            ->first();
    }

    /**
     * Check if room can be borrowed
     */
    public function bisaDipinjam()
    {
        return $this->status === 'tersedia' && !$this->isCurrentlyBorrowed();
    }

    /**
     * Effective status considering active borrowings
     * - If there is an active borrowing, treat as 'dipinjam' regardless of saved status
     */
    public function getEffectiveStatusAttribute()
    {
        return $this->isCurrentlyBorrowed() ? 'dipinjam' : $this->status;
    }

    /**
     * Update room status to borrowed
     */
    public function setBorrowed()
    {
        $this->status = 'dipinjam';
        $this->save();
    }

    /**
     * Update room status to available
     */
    public function setAvailable()
    {
        $this->status = 'tersedia';
        $this->save();
    }

    /**
     * Update room status to maintenance
     */
    public function setMaintenance()
    {
        $this->status = 'maintenance';
        $this->save();
    }

    /**
     * Get all photos for this room
     */
    public function getPhotosAttribute()
    {
        $photos = [];
        if ($this->foto1) $photos[] = $this->foto1;
        if ($this->foto2) $photos[] = $this->foto2;
        if ($this->foto3) $photos[] = $this->foto3;
        return $photos;
    }

    /**
     * Get the main photo (first available photo)
     */
    public function getMainPhotoAttribute()
    {
        return $this->foto1 ?: asset('assets/images/placeholder-image.svg');
    }

    /**
     * Check if room has photos
     */
    public function hasPhotos()
    {
        return !empty($this->foto1) || !empty($this->foto2) || !empty($this->foto3);
    }

    /**
     * Get photo count
     */
    public function getPhotoCountAttribute()
    {
        return count($this->photos);
    }
}
