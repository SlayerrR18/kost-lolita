<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    // ------------- Konstanta biar gak magic string -------------
    public const STATUS_PENDING   = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_REJECTED  = 'rejected';

    public const TYPE_NEW        = 'new';
    public const TYPE_EXTENSION  = 'extension';

    protected $fillable = [
        'user_id','kost_id','name','email','phone','alamat',
        'duration','tanggal_masuk','tanggal_keluar','status',
        'ktp_image','bukti_pembayaran','type','parent_order_id','confirmed_at',
    ];

    protected $casts = [
        'tanggal_masuk'  => 'date',
        'tanggal_keluar' => 'date',
        'confirmed_at'   => 'datetime',
    ];

    // Kalau sering butuh harga/nomor_kamar, ini membantu menghindari N+1
    protected $with = ['kost'];

    protected $appends = [
        'ktp_image_url',
        'bukti_pembayaran_url',
        'is_extension',
        // Tambahan berguna:
        'period_text',
        'status_label',
        'type_label',
    ];

    // ------------------- Relations -------------------
    public function kost()    { return $this->belongsTo(Kost::class); }
    public function user()    { return $this->belongsTo(User::class); }
    public function parent()  { return $this->belongsTo(self::class, 'parent_order_id'); }
    public function children(){ return $this->hasMany(self::class, 'parent_order_id'); }

    // ------------------- Scopes -------------------
    public function scopePending(Builder $q)   { return $q->where('status', self::STATUS_PENDING); }
    public function scopeConfirmed(Builder $q) { return $q->where('status', self::STATUS_CONFIRMED); }
    public function scopeExtension(Builder $q) { return $q->where('type', self::TYPE_EXTENSION); }
    public function scopeForUser(Builder $q, $userId) { return $q->where('user_id', $userId); }

    // Kontrak aktif pada tanggal tertentu (default: hari ini)
    public function scopeActiveOn(Builder $q, $date = null)
    {
        $date = $date ?: now()->toDateString();
        return $q->where('status', self::STATUS_CONFIRMED)
                 ->where('tanggal_masuk', '<=', $date)
                 ->where('tanggal_keluar', '>=', $date);
    }

    // Cek overlap periode (berguna juga di controller admin)
    public function scopeOverlaps(Builder $q, $kostId, $start, $end)
    {
        return $q->where('kost_id', $kostId)
                 ->where('status', self::STATUS_CONFIRMED)
                 ->where(function ($qq) use ($start, $end) {
                     $qq->whereBetween('tanggal_masuk',  [$start, $end])
                        ->orWhereBetween('tanggal_keluar', [$start, $end])
                        ->orWhere(function ($q2) use ($start, $end) {
                            $q2->where('tanggal_masuk', '<=', $start)
                               ->where('tanggal_keluar', '>=', $end);
                        });
                 });
    }

    // ------------------- Helpers -------------------
    public function isPending(): bool   { return $this->status === self::STATUS_PENDING; }
    public function isConfirmed(): bool { return $this->status === self::STATUS_CONFIRMED; }
    public function isRejected(): bool  { return $this->status === self::STATUS_REJECTED; }

    public function getIsExtensionAttribute(): bool
    {
        return ($this->type ?? null) === self::TYPE_EXTENSION || !is_null($this->parent_order_id);
    }

    public function totalAmount(): int
    {
        $harga = (int) optional($this->kost)->harga;
        return $harga * (int) $this->duration;
    }

    // Tambahkan accessor untuk URL gambar
    public function getKtpImageUrlAttribute()
    {
        if (!$this->ktp_image) {
            return null;
        }

        // Ensure we have a clean path without 'public/'
        $path = str_replace('public/', '', $this->ktp_image);

        // For debugging
        \Log::info('KTP Image Path Check:', [
            'original' => $this->ktp_image,
            'cleaned' => $path,
            'full_url' => asset('storage/' . $path),
            'exists' => Storage::disk('public')->exists($path)
        ]);

        return asset('storage/' . $path);
    }

    public function getBuktiPembayaranUrlAttribute(): ?string
    {
        if (!$this->bukti_pembayaran) {
            return null;
        }
        return Storage::disk('public')->url($this->bukti_pembayaran);
    }

    // Tambahan aksesori presentasi yang sering dipakai di UI
    public function getPeriodTextAttribute(): string
    {
        if (!$this->tanggal_masuk || !$this->tanggal_keluar) return '-';
        return $this->tanggal_masuk->format('d M Y') . ' — ' . $this->tanggal_keluar->format('d M Y');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_REJECTED  => 'Rejected',
            default                => 'Pending',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->is_extension ? 'Perpanjangan' : 'Baru';
    }

    // ------------------- Defaults di layer model -------------------
    protected static function booted()
    {
        static::creating(function (self $order) {
            // default aman biarpun DB sudah punya default
            $order->status ??= self::STATUS_PENDING;
            $order->type   ??= self::TYPE_NEW;
        });
    }
}
