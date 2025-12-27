<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'parent_order_id',
        'type',
        'email',
        'full_name',
        'phone',
        'emergency_phone',
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


   public function getEndDateAttribute()
    {
        if (!$this->start_date || !$this->rent_duration) {
            return null;
        }
        return Carbon::parse($this->start_date)->addMonths($this->rent_duration);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function parentOrder()
    {
        return $this->belongsTo(Order::class, 'parent_order_id');
    }

}
