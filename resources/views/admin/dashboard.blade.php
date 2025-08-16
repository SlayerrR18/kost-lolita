{{-- filepath: d:\Kampus\Web\kost-lolita\resources\views\admin\dashboard.blade.php --}}
@extends('layouts.main')

@section('title', 'Dashboard Admin')

@push('css')
<style>
    :root {
        --primary: #1a7f5a;
        --primary-2: #16c79a;
        --primary-light: rgba(26,127,90,0.1);
        --bg: #f8fafc;
        --ink: #1e293b;
        --muted: #64748b;
        --ring: #e2e8f0;
        --success: #22c55e;
        --warning: #f59e0b;
        --danger: #ef4444;
    }

    /* Layout Container */
    .dashboard-container {
        padding: 2rem;
        background: var(--bg);
        min-height: 100vh;
    }

    .container-fluid {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    /* Modern Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: #fff;
        box-shadow: 0 4px 20px rgba(26,127,90,.15);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }

    .page-subtitle {
        opacity: 0.9;
        margin-top: 0.5rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .finance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    /* Modern Cards */
    .stat-card {
        background: #fff;
        border-radius: 24px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .stat-content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        position: relative;
        z-index: 1;
    }

    .stat-header {
        margin-bottom: 1rem;
    }

    .stat-title {
        color: var(--muted);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .stat-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0.5rem 0;
    }

    .stat-trend {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    /* Chart Container */
    .chart-container {
        background: #fff;
        border-radius: 24px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin-top: 2rem;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--ink);
        margin: 0;
    }

    /* Filter Controls */
    .filter-container {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .form-select {
        padding: 0.5rem 1rem;
        border: 1px solid var(--ring);
        border-radius: 12px;
        font-size: 0.875rem;
        min-width: 150px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .filter-container {
            width: 100%;
        }

        .form-select {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="page-header">
            <div class="header-content">
                <div>
                    <h1 class="page-title">Dashboard Admin</h1>
                    <p class="page-subtitle">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="filter-container">
                    <form id="filterForm" class="d-flex gap-3 flex-wrap">
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
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <!-- Kamar Stats -->
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-header">
                        <div class="stat-title">Kamar Kosong</div>
                        <div class="stat-value">{{ $total_kamar_kosong }}</div>
                    </div>
                    <div class="stat-trend text-success">
                        <i data-feather="home"></i>
                        <span>Total Kamar Tersedia</span>
                    </div>
                </div>
            </div>

            <!-- Penghuni Stats -->
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-header">
                        <div class="stat-title">Penghuni Aktif</div>
                        <div class="stat-value">{{ $total_penghuni }}</div>
                    </div>
                    <div class="stat-trend text-primary">
                        <i data-feather="users"></i>
                        <span>Total Penghuni</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finance Grid -->
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
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-container">
            <div class="chart-header">
                <h2 class="chart-title">Grafik Keuangan</h2>
            </div>
            <canvas id="monthlyChart" height="100"></canvas>
        </div>
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
