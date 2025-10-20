<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'kost_id',
        'tanggal_masuk',
        'tanggal_keluar',
        'penghuni',
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
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($user) {
            // Delete related orders
            $user->orders()->delete();

            // Delete related kost records
            if($user->kost) {
                $user->kost->update([
                    'status' => 'Kosong',
                    'penghuni' => null
                ]);
            }
        });
    }

    public function kost()
    {
        return $this->belongsTo(Kost::class, 'kost_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    public function adminMessages()
    {
        return $this->hasMany(Message::class, 'admin_id');
    }

    public function hasApprovedOrder()
    {
        return $this->orders()
            ->where('status', 'confirmed')
            ->exists();
    }
}
