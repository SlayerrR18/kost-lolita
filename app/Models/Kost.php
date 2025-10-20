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

    protected $casts = [
        'fasilitas' => 'array',
        'foto' => 'array'
    ];

    public function isAvailable()
    {
        return $this->status === 'Kosong';
    }

    public function getFasilitasAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    public function setFasilitasAttribute($value)
    {
        $this->attributes['fasilitas'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getFotoAttribute($value)
    {
        // Jika value kosong, return array kosong
        if (empty($value)) {
            return [];
        }

        // Jika value sudah berbentuk array, return langsung
        if (is_array($value)) {
            return $value;
        }

        // Jika value string JSON, decode
        try {
            $decoded = json_decode($value, true);
            return $decoded ?: [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function setFotoAttribute($value)
    {
        // Jika input array, encode ke JSON
        if (is_array($value)) {
            $this->attributes['foto'] = json_encode($value);
        }
        // Jika input string (JSON atau biasa), simpan apa adanya
        else {
            $this->attributes['foto'] = $value;
        }
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
