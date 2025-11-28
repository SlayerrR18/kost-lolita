<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number', 'price', 'facilities', 'photos', 'status'
    ];


    protected $casts = [
        'facilities' => 'array',
        'photos' => 'array',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
