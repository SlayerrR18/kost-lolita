<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kost_id',
        'name',
        'email',
        'phone',
        'alamat',
        'duration',
        'tanggal_masuk',
        'tanggal_keluar',
        'status',
        'ktp_image',
        'bukti_pembayaran',

        'type',
        'parent_order_id',
        'confirmed_at',
    ];

    protected $casts = [
        'tanggal_masuk'  => 'date',
        'tanggal_keluar' => 'date',
        'confirmed_at'   => 'datetime',
    ];


    protected $appends = ['ktp_image_url', 'bukti_pembayaran_url', 'is_extension'];

    public function kost()   { return $this->belongsTo(Kost::class); }
    public function user()   { return $this->belongsTo(User::class); }
    public function parent() { return $this->belongsTo(self::class, 'parent_order_id'); }
    public function children(){ return $this->hasMany(self::class, 'parent_order_id'); }


    public function scopePending(Builder $q)   { return $q->where('status', 'pending'); }
    public function scopeConfirmed(Builder $q) { return $q->where('status', 'confirmed'); }
    public function scopeExtension(Builder $q) { return $q->where('type', 'extension'); }
    public function scopeForUser(Builder $q, $userId) { return $q->where('user_id', $userId); }


    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isConfirmed(): bool { return $this->status === 'confirmed'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }


    public function getIsExtensionAttribute(): bool
    {
        return ($this->type ?? null) === 'extension' || !is_null($this->parent_order_id);
    }


    public function totalAmount(): int
    {
        $harga = (int) optional($this->kost)->harga;
        return $harga * (int) $this->duration;
    }
public function getKtpImageUrlAttribute(): ?string
    {
        // Jika order ini punya KTP, pakai itu
        if ($this->ktp_image && Storage::disk('public')->exists($this->ktp_image)) {
            return Storage::url($this->ktp_image);
        }

        // Fallback (opsional): cari KTP pertama user ini
        if ($this->user_id) {
            $fallback = static::where('user_id', $this->user_id)
                ->whereNotNull('ktp_image')
                ->orderBy('id')      // ambil pertama kali upload
                ->value('ktp_image');

            if ($fallback && Storage::disk('public')->exists($fallback)) {
                return Storage::url($fallback);
            }
        }

        return null;
    }

    public function getBuktiPembayaranUrlAttribute(): ?string
    {
        return ($this->bukti_pembayaran && Storage::disk('public')->exists($this->bukti_pembayaran))
            ? Storage::url($this->bukti_pembayaran)
            : null;
    }

}
