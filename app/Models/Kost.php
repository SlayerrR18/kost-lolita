<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kost extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_kamar',
        'fasilitas',
        'foto',
        'status',
        'harga',
        'penghuni'
    ];

    protected $dates = [
        'tanggal_masuk',
        'tanggal_keluar'
    ];

    public function isAvailable()
    {
        return $this->status === 'Kosong';
    }

    // Accessor untuk memastikan fasilitas selalu array
    public function getFasilitasAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    // Mutator untuk memastikan fasilitas disimpan sebagai JSON string
    public function setFasilitasAttribute($value)
    {
        $this->attributes['fasilitas'] = is_array($value) ? json_encode($value) : $value;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
