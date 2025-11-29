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

        // List tahun yang tersedia di DB (untuk dropdown)
        $availableYears = Income::selectRaw('YEAR(date) as year')
            ->union(Expense::selectRaw('YEAR(date) as year'))
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        if (empty($availableYears)) $availableYears = [now()->year];

        // 2. Card Stats (Berdasarkan Bulan & Tahun yang dipilih)
        $totalrooms = Room::count();
        $availablerooms = Room::where('status', 'available')->count();

        // Penghuni Aktif (User yang punya order approved & masih dalam periode sewa)
        $activeusers = User::whereHas('orders', function ($query) {
            $query->where('status', 'approved');
        })->count();

        // Sample Avatar User
        $activeUserSamples = User::whereHas('orders', function ($query) {
            $query->where('status', 'approved');
        })->take(5)->get();

        // Hitung Keuangan Bulan Ini (Selected) vs Bulan Lalu
        $currentIncome = Income::whereYear('date', $selectedYear)->whereMonth('date', $selectedMonth)->sum('amount');
        $currentExpense = Expense::whereYear('date', $selectedYear)->whereMonth('date', $selectedMonth)->sum('amount');
        $currentProfit = $currentIncome - $currentExpense;

        // Hitung Bulan Sebelumnya untuk Komparasi
        $lastDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->subMonth();
        $lastProfit = Income::whereYear('date', $lastDate->year)->whereMonth('date', $lastDate->month)->sum('amount')
                    - Expense::whereYear('date', $lastDate->year)->whereMonth('date', $lastDate->month)->sum('amount');

        // Persentase Kenaikan/Penurunan Profit
        $profitPercentage = 0;
        $profitTrend = 'neutral'; // up, down, neutral
        if ($lastProfit > 0) {
            $profitPercentage = (($currentProfit - $lastProfit) / $lastProfit) * 100;
            $profitTrend = $profitPercentage > 0 ? 'up' : 'down';
        } elseif ($lastProfit == 0 && $currentProfit > 0) {
            $profitPercentage = 100;
            $profitTrend = 'up';
        }

        // 3. Main Chart Data (Selalu Tampilkan 1 Tahun Penuh dari Tahun yang Dipilih)
        $monthlyIncomes = [];
        $monthlyExpenses = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyIncomes[] = (int) Income::whereYear('date', $selectedYear)->whereMonth('date', $m)->sum('amount');
            $monthlyExpenses[] = (int) Expense::whereYear('date', $selectedYear)->whereMonth('date', $m)->sum('amount');
        }

        // 4. Expense Distribution & Top Categories (Berdasarkan Tahun yang Dipilih)
        $expenseByCategory = Expense::whereYear('date', $selectedYear)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $totalExpenseYearly = $expenseByCategory->sum('total');

        // Warna Chart Konsisten
        $chartColors = ['#222831', '#DFD0B8', '#9CA3AF', '#60A5FA', '#F97316', '#10B981'];

        $topExpenseCategories = $expenseByCategory->take(3)->map(function ($item, $key) use ($totalExpenseYearly, $chartColors) {
            $item->formatted_total = 'Rp ' . number_format($item->total, 0, ',', '.');
            // Hindari division by zero
            $item->percentage = $totalExpenseYearly > 0 ? round(($item->total / $totalExpenseYearly) * 100, 1) : 0;
            $item->color = $chartColors[$key] ?? '#cccccc';
            return $item;
        });

        // Data untuk Donut Chart
        $expenseLabels = $expenseByCategory->pluck('category');
        $expenseValues = $expenseByCategory->pluck('total');

        // 5. Recent Payments
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
