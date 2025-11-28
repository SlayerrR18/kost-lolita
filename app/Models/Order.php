<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'email',
        'full_name',
        'phone',
        'address',
        'id_number',
        'id_photo_path',
        'rent_duration',
        'start_date',
        'transfer_proof_path',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
