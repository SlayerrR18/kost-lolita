<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'description',
        'amount',
        'date',
        'category',
        'payment_method',
        'reference',
        'order_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes untuk filter
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
    }

    // Helper methods
    public static function getTotalIncome($startDate = null, $endDate = null)
    {
        $query = self::query();
        if ($startDate && $endDate) {
            $query->byDateRange($startDate, $endDate);
        }
        return $query->sum('amount');
    }

    public static function getTotalByCategory($category, $startDate = null, $endDate = null)
    {
        $query = self::byCategory($category);
        if ($startDate && $endDate) {
            $query->byDateRange($startDate, $endDate);
        }
        return $query->sum('amount');
    }

    public static function getCategoryOptions()
    {
        return [
            'room_rent' => 'Sewa Kamar',
            'deposit' => 'Deposit Kamar',
            'services' => 'Layanan Tambahan',
            'other' => 'Lain-lain',
        ];
    }
}
