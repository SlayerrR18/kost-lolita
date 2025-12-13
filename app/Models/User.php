<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'emergency_contact',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'emergency_contact' => 'array',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Current room (via last approved order)
     * Uses hasOneThrough from User -> Order -> Room filtered to approved orders.
     */
    public function room()
    {
        return $this->hasOneThrough(
            Room::class,
            Order::class,
            'user_id', // Foreign key on orders table...
            'id',      // Foreign key on rooms table (primary key)
            'id',      // Local key on users table
            'room_id'  // Local key on orders table
        )->where('orders.status', 'approved')
         ->orderBy('orders.start_date', 'desc');
    }

    public function sentMessages() {
    return $this->hasMany(Message::class, 'sender_id');
    }
    public function receivedMessages() {
        return $this->hasMany(Message::class, 'recipient_id');
    }

}
