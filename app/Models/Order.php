<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kost_id',
        'name',
        'email',
        'status',
        'ktp_image',
        'bukti_pembayaran',
        'phone',
        'alamat', 
        'tanggal_masuk',
        'tanggal_keluar',
        'duration'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date'
    ];

    protected $dates = [
        'tanggal_masuk',
        'tanggal_keluar'
    ];

    // Relationship with Kost
    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Accessor for KTP image URL
    public function getKtpImageUrlAttribute()
    {
        return asset('storage/' . $this->ktp_image);
    }

    // Accessor for payment proof URL
    public function getBuktiPembayaranUrlAttribute()
    {
        if ($this->bukti_pembayaran) {
            return Storage::url($this->bukti_pembayaran);
        }
        return null;
    }
}
