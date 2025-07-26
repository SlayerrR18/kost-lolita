<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Kost;
use App\Models\Financial;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index (Request $request)
    {

        $total_kamar_kosong = Kost::where('status', 'Kosong')->count();
        $total_penghuni = Kost::where('status', 'Terisi')->count();
        $total_pengeluaran = Financial::where('status', 'Pengeluaran')->sum('total');
        $total_pemasukan_kotor = Financial::where('status', 'Pemasukan')->sum('total');


        $total_pemasukan = $total_pemasukan_kotor - $total_pengeluaran;

        // Get filter parameters
        $year = $request->get('year', now()->year);
        $month = $request->get('month');
        $type = $request->get('type', 'all');

        // Build base query
        $query = Financial::query();

        // Apply year filter
        $query->whereYear('tanggal_transaksi', $year);

        // Apply month filter if selected
        if ($month) {
            $query->whereMonth('tanggal_transaksi', $month);
        }

        // Get monthly data with proper ordering
        $monthlyData = $query->selectRaw('
                MONTH(tanggal_transaksi) as bulan,
                SUM(CASE WHEN status = "Pemasukan" THEN total ELSE 0 END) as income,
                SUM(CASE WHEN status = "Pengeluaran" THEN total ELSE 0 END) as expense
            ')
            ->groupBy('bulan')
            ->orderByRaw('bulan ASC')
            ->get();

        // Format data for charts
        $months = [];
        $incomeData = [];
        $expenseData = [];
        $profitData = [];

        foreach ($monthlyData as $data) {
            $months[] = date('F', mktime(0, 0, 0, $data->bulan, 1));
            $incomeData[] = (float)$data->income;
            $expenseData[] = (float)$data->expense;
            $profitData[] = (float)($data->income - $data->expense);
        }

        // Calculate yearly totals
        $yearlyTotals = [
            'income' => array_sum($incomeData),
            'expense' => array_sum($expenseData),
            'profit' => array_sum($profitData)
        ];

        // Get available years for filter
        $availableYears = Financial::selectRaw('YEAR(tanggal_transaksi) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.dashboard', compact(
            'total_kamar_kosong',
            'total_penghuni',
            'total_pengeluaran',
            'total_pemasukan',
            'total_pemasukan_kotor',
            'availableYears',
            'year',
            'month',
            'type',
            'yearlyTotals',
            'incomeData',
            'expenseData',
            'profitData',
            'months'
        ));
    }
}
