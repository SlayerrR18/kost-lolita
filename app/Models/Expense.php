<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'date',
        'category',
        'payment_method',
        'reference',
        'bukti_transfer',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

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
    public static function getTotalExpense($startDate = null, $endDate = null)
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
            'maintenance' => 'Pemeliharaan',
            'utilities' => 'Utilitas (Air, Listrik, Internet)',
            'supplies' => 'Perlengkapan',
            'repairs' => 'Perbaikan',
            'staff_wages' => 'Gaji Karyawan',
            'marketing' => 'Marketing',
            'other' => 'Lain-lain',
        ];
    }
}
