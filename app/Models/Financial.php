<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Financial extends Model
{
    protected $fillable = [
        'kost_id',
        'nama_transaksi',
        'tanggal_transaksi',
        'total',
        'status',
        'bukti_pembayaran',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'total' => 'decimal:2',
    ];

    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }

    public function getBuktiPembayaranUrlAttribute(): ?string
    {
        return $this->bukti_pembayaran
            ? Storage::disk('public')->url($this->bukti_pembayaran)
            : null;
    }
}
