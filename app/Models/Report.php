<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'photo',
        'date',
        'status',
        'response',
        'handler_id',
        'response_photo', // Baru
        'processing_at',  // Baru
        'completed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'processing_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relasi ke User (Pelapor)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Admin (Penanggung Jawab)
    public function handler()
    {
        return $this->belongsTo(User::class, 'handler_id');
    }
}
