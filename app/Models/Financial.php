<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Financial extends Model
{
    protected $casts = [
        'tanggal_transaksi' => 'date',
        'total' => 'float'
    ];

    protected $fillable = [
        'kost_id',
        'nama_transaksi',
        'tanggal_transaksi',
        'total',
        'status',
        'bukti_pembayaran'
    ];

    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }

    // Add accessor for image URL
    public function getBuktiPembayaranUrlAttribute()
    {
        if ($this->bukti_pembayaran) {
            return Storage::url($this->bukti_pembayaran);
        }
        return null;
    }
}
