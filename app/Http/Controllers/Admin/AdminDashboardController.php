<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filter Setup
        $selectedYear = (int) $request->input('year', now()->year);
        $selectedMonth = (int) $request->input('month', now()->month);

        $availableYears = Income::selectRaw('YEAR(date) as year')
            ->union(Expense::selectRaw('YEAR(date) as year'))
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        if (empty($availableYears)) $availableYears = [now()->year];

        // Card Metrics
        $totalrooms = Room::count();
        $availablerooms = Room::where('status', 'available')->count();

        // Penghuni Aktif
        $activeusers = User::whereHas('orders', function ($query) {
            $query->where('status', 'approved');
        })->count();

        $activeUserSamples = User::whereHas('orders', function ($query) {
            $query->where('status', 'approved');
        })->take(5)->get();

        // Keuangan
        $currentIncome = Income::whereYear('date', $selectedYear)->whereMonth('date', $selectedMonth)->sum('amount');
        $currentExpense = Expense::whereYear('date', $selectedYear)->whereMonth('date', $selectedMonth)->sum('amount');
        $currentProfit = $currentIncome - $currentExpense;

        $lastDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->subMonth();
        $lastProfit = Income::whereYear('date', $lastDate->year)->whereMonth('date', $lastDate->month)->sum('amount')
                    - Expense::whereYear('date', $lastDate->year)->whereMonth('date', $lastDate->month)->sum('amount');

        // Perhitungan Profit
        $profitPercentage = 0;
        $profitTrend = 'neutral';
        if ($lastProfit > 0) {
            $profitPercentage = (($currentProfit - $lastProfit) / $lastProfit) * 100;
            $profitTrend = $profitPercentage > 0 ? 'up' : 'down';
        } elseif ($lastProfit == 0 && $currentProfit > 0) {
            $profitPercentage = 100;
            $profitTrend = 'up';
        }

        $monthlyIncomes = [];
        $monthlyExpenses = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyIncomes[] = (int) Income::whereYear('date', $selectedYear)->whereMonth('date', $m)->sum('amount');
            $monthlyExpenses[] = (int) Expense::whereYear('date', $selectedYear)->whereMonth('date', $m)->sum('amount');
        }

        $expenseByCategory = Expense::whereYear('date', $selectedYear)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $totalExpenseYearly = $expenseByCategory->sum('total');

        $chartColors = ['#222831', '#DFD0B8', '#9CA3AF', '#60A5FA', '#F97316', '#10B981'];

        $topExpenseCategories = $expenseByCategory->take(3)->map(function ($item, $key) use ($totalExpenseYearly, $chartColors) {
            $item->formatted_total = 'Rp ' . number_format($item->total, 0, ',', '.');
            $item->percentage = $totalExpenseYearly > 0 ? round(($item->total / $totalExpenseYearly) * 100, 1) : 0;
            $item->color = $chartColors[$key] ?? '#cccccc';
            return $item;
        });

        // Donut Chart
        $expenseLabels = $expenseByCategory->pluck('category');
        $expenseValues = $expenseByCategory->pluck('total');

        // Pembayaran Terbaru
        $recentPayments = Income::with(['order.user', 'order.room'])
            ->whereNotNull('order_id')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalrooms', 'availablerooms', 'activeusers', 'activeUserSamples',
            'currentProfit', 'profitPercentage', 'profitTrend',
            'monthlyIncomes', 'monthlyExpenses',
            'expenseLabels', 'expenseValues',
            'topExpenseCategories', 'expenseByCategory',
            'recentPayments',
            'selectedYear', 'selectedMonth', 'availableYears'
        ));
    }
}
