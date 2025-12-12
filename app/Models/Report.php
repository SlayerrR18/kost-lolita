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
        'handler_id'
    ];

    protected $casts = [
        'date' => 'date',
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
