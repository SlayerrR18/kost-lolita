{{-- filepath: d:\Kampus\Web\kost-lolita\resources\views\admin\dashboard.blade.php --}}
@extends('layouts.main')

@section('title', 'Dashboard Admin')

@push('css')
<style>
    /* Base Styles */
    :root {
        --primary: #1a7f5a;
        --primary-light: #16c79a;
        --secondary: #64748b;
        --success: #10B981;
        --danger: #EF4444;
        --warning: #F59E0B;
        --info: #3B82F6;
    }

    /* Dashboard Layout */
    .dashboard-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 2rem;
        background: #f8fafc;
    }

    /* Header Section */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .header-content {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .dashboard-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary);
        margin: 0;
    }

    .welcome-date {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-areas:
            "kamar penghuni"
            "finance finance";
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .finance-grid {
        grid-area: finance;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .stat-card {
        position: relative;
        padding: 1.5rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .stat-content {
        position: relative;
        z-index: 1;
    }

    .stat-title {
        color: var(--secondary);
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .stat-icon {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.1;
        transform: rotate(-15deg);
        transition: all 0.3s ease;
    }

    /* Update card specific styles */
    .stat-card.kamar {
        grid-area: kamar;
    }

    .stat-card.penghuni {
        grid-area: penghuni;
    }

    /* Chart Section */
    .chart-container {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-top: 2rem;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .filter-container {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .form-select {
        min-width: 150px;
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background-color: white;
        font-size: 0.9rem;
    }

    /* Summary Cards */
    .summary-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            grid-template-areas:
                "kamar"
                "penghuni"
                "finance";
        }

        .finance-grid {
            grid-template-columns: 1fr;
        }

        .filter-container {
            flex-direction: column;
        }

        .form-select {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="dashboard-title">Dashboard </h1>
            <div class="welcome-date">
                <i data-feather="calendar"></i>
                {{ now()->format('d F Y') }}
            </div>
        </div>
        <div class="filter-container">
            <form id="filterForm" class="d-flex gap-3">
                <select name="year" class="form-select" onchange="this.form.submit()">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                            Tahun {{ $y }}
                        </option>
                    @endforeach
                </select>

                <select name="month" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>

                <select name="type" class="form-select" onchange="updateChartType(this.value)">
                    <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Semua Data</option>
                    <option value="income" {{ $type == 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ $type == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    <option value="profit" {{ $type == 'profit' ? 'selected' : '' }}>Profit</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Kamar Kosong Card -->
        <div class="stat-card kamar">
            <div class="stat-content">
                <div class="stat-title">Kamar Kosong</div>
                <div class="stat-value">{{ $total_kamar_kosong }}</div>
                <div class="stat-trend text-success">
                    <i data-feather="home"></i>
                    Total Kamar Tersedia
                </div>
            </div>
            <div class="stat-icon">
                <i data-feather="home"></i>
            </div>
        </div>

        <!-- Penghuni Card -->
        <div class="stat-card penghuni">
            <div class="stat-content">
                <div class="stat-title">Penghuni Aktif</div>
                <div class="stat-value">{{ $total_penghuni }}</div>
                <div class="stat-trend text-primary">
                    <i data-feather="users"></i>
                    Total Penghuni
                </div>
            </div>
            <div class="stat-icon">
                <i data-feather="users"></i>
            </div>
        </div>

        <!-- Finance Cards -->
        <div class="finance-grid">
            <!-- Pemasukan Card -->
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-title">Total Pemasukan</div>
                    <div class="stat-value">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</div>
                    <div class="stat-trend text-success">
                        <i data-feather="trending-up"></i>
                        Pendapatan
                    </div>
                </div>
                <div class="stat-icon">
                    <i data-feather="arrow-up"></i>
                </div>
            </div>

            <!-- Pengeluaran Card -->
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-title">Total Pengeluaran</div>
                    <div class="stat-value">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</div>
                    <div class="stat-trend text-danger">
                        <i data-feather="trending-down"></i>
                        Biaya
                    </div>
                </div>
                <div class="stat-icon">
                    <i data-feather="arrow-down"></i>
                </div>
            </div>

            <!-- Profit Card -->
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-title">Total Profit</div>
                    <div class="stat-value">Rp {{ number_format($total_pemasukan - $total_pengeluaran, 0, ',', '.') }}</div>
                    <div class="stat-trend text-info">
                        <i data-feather="dollar-sign"></i>
                        Keuntungan Bersih
                    </div>
                </div>
                <div class="stat-icon">
                    <i data-feather="trending-up"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="chart-container">
        <div class="chart-header">
            <h2>Grafik Keuangan</h2>
        </div>
        <canvas id="monthlyChart" height="100"></canvas>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>
<script>
let myChart;

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyChart').getContext('2d');

    const datasets = {
        income: {
            label: 'Pemasukan',
            data: @json($incomeData),
            borderColor: '#10B981',
            backgroundColor: '#10B98120',
            fill: true,
            tension: 0.4
        },
        expense: {
            label: 'Pengeluaran',
            data: @json($expenseData),
            borderColor: '#EF4444',
            backgroundColor: '#EF444420',
            fill: true,
            tension: 0.4
        },
        profit: {
            label: 'Profit',
            data: @json($profitData),
            borderColor: '#3B82F6',
            backgroundColor: '#3B82F620',
            fill: true,
            tension: 0.4
        }
    };

    function createChart(type = 'all') {
        const activeDatasets = [];

        if (type === 'all' || type === 'income') activeDatasets.push(datasets.income);
        if (type === 'all' || type === 'expense') activeDatasets.push(datasets.expense);
        if (type === 'all' || type === 'profit') activeDatasets.push(datasets.profit);

        if (myChart) {
            myChart.destroy();
        }

        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: activeDatasets
            },
            options: {
                responsive: true,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    }

    // Initial chart creation
    createChart('{{ $type }}');

    // Make createChart available globally
    window.updateChartType = function(type) {
        createChart(type);
    };

    // Initialize Feather Icons
    feather.replace();
});
</script>
@endpush
