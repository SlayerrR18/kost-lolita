{{-- filepath: d:\Kampus\Web\kost-lolita\resources\views\admin\dashboard.blade.php --}}
@extends('layouts.main')

@section('title', 'Dashboard Admin')

@push('css')
<style>
    /* === Palet & Umum (diselaraskan dengan desain sebelumnya) === */
    :root {
        --primary: #1a7f5a;
        --primary-2: #16c79a;
        --secondary: #f1f5f9;
        --surface: #ffffff;
        --bg: #f8fafc;
        --ink: #1e293b;
        --muted: #64748b;
        --ring: #e2e8f0;
        --success: #16a34a;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #0ea5e9;
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 24px;
        --radius-pill: 9999px;
    }

    body {
        font-family: 'Poppins', sans-serif;
        color: var(--ink);
    }

    /* === Layout & Containers === */
    .dashboard-container {
        padding: 2rem;
        background: var(--bg);
        min-height: 100vh;
    }

    .container-fluid {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 0;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        color: #fff;
        box-shadow: 0 4px 20px rgba(26, 127, 90, .15);
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

    /* === Stats Grid === */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* === Modern Cards === */
    .stat-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .stat-header {
        margin-bottom: 1rem;
    }

    .stat-title {
        color: var(--muted);
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0;
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--ink);
        margin: 0.5rem 0;
    }

    .stat-value.currency {
        font-size: 1.75rem;
    }

    .stat-foot {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .stat-foot-icon {
        width: 1rem;
        height: 1rem;
    }

    .text-primary-color { color: var(--primary); }
    .text-success-color { color: var(--success); }
    .text-danger-color { color: var(--danger); }
    .text-info-color { color: var(--info); }

    /* === Chart Section === */
    .chart-container {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        margin-top: 2rem;
        height: 400px;
        position: relative;
    }

    #monthlyChart {
        height: 100% !important;
        width: 100% !important;
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

    /* === Filter Controls (IMPROVED) === */
    .filter-container {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-container .form-select,
    .filter-container .btn-apply-filter {
        height: 38px;
    }

    .form-select {
        padding: 0.5rem 1rem;
        border: 1px solid var(--ring);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        min-width: 150px;
        color: var(--ink);
        background-color: var(--surface);
    }

    .btn-apply-filter {
        background: white;
        color: var(--primary);
        border: none;
        border-radius: var(--radius-md);
        padding: 0.5rem 1rem;
        font-weight: 500;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-md);
    }

    .btn-apply-filter:hover {
        background: var(--secondary);
        transform: translateY(-1px);
        color: var(--primary);
    }

    /* === Responsive Adjustments === */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header .filter-container {
            width: 100%;
        }

        .filter-container {
            flex-direction: column;
            align-items: stretch;
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
        {{-- Header Section --}}
        <div class="page-header">
            <div class="header-content">
                <div>
                    <h1 class="page-title">Dashboard Admin</h1>
                    <p class="page-subtitle">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                {{-- Filter controls dipindahkan ke sini --}}
                <div class="filter-container">
                    <form id="filterForm" class="d-flex gap-3 flex-wrap">
                        <select id="filterYear" name="year" class="form-select">
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    Tahun {{ $y }}
                                </option>
                            @endforeach
                        </select>
                        <select id="filterMonth" name="month" class="form-select">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-apply-filter" id="applyFilterBtn">
                            <i data-feather="filter"></i> Terapkan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-header">
                        <div class="stat-title">Kamar Kosong</div>
                        <div class="stat-value">{{ $total_kamar_kosong }}</div>
                    </div>
                    <div class="stat-foot text-primary-color">
                        <i data-feather="home" class="stat-foot-icon"></i>
                        <span>Total Kamar Tersedia</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-header">
                        <div class="stat-title">Penghuni Aktif</div>
                        <div class="stat-value">{{ $total_penghuni }}</div>
                    </div>
                    <div class="stat-foot text-primary-color">
                        <i data-feather="users" class="stat-foot-icon"></i>
                        <span>Total Penghuni</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Finance Grid --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-title">Total Pemasukan</div>
                    <div class="stat-value currency">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</div>
                    <div class="stat-foot text-success-color">
                        <i data-feather="trending-up" class="stat-foot-icon"></i>
                        <span>Pendapatan</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-title">Total Pengeluaran</div>
                    <div class="stat-value currency">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</div>
                    <div class="stat-foot text-danger-color">
                        <i data-feather="trending-down" class="stat-foot-icon"></i>
                        <span>Biaya</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-title">Total Profit</div>
                    <div class="stat-value currency">Rp {{ number_format($total_pemasukan - $total_pengeluaran, 0, ',', '.') }}</div>
                    <div class="stat-foot text-info-color">
                        <i data-feather="dollar-sign" class="stat-foot-icon"></i>
                        <span>Keuntungan Bersih</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Section --}}
        <div class="chart-container">
            <div class="chart-header">
                <h2 class="chart-title">Grafik Keuangan</h2>
                {{-- Filter tipe data chart dipindahkan ke sini --}}
                <div class="filter-container">
                    <select id="chartType" class="form-select">
                        <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Semua Data</option>
                        <option value="income" {{ $type == 'income' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="expense" {{ $type == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                        <option value="profit" {{ $type == 'profit' ? 'selected' : '' }}>Profit</option>
                    </select>
                </div>
            </div>
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();

        let myChart;
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const filterYear = document.getElementById('filterYear');
        const filterMonth = document.getElementById('filterMonth');
        const filterForm = document.getElementById('filterForm');
        const chartTypeSelect = document.getElementById('chartType');

        const datasets = {
            income: {
                label: 'Pemasukan',
                data: @json($incomeData),
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22, 163, 74, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointRadius: 5
            },
            expense: {
                label: 'Pengeluaran',
                data: @json($expenseData),
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointRadius: 5
            },
            profit: {
                label: 'Profit',
                data: @json($profitData),
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointRadius: 5
            }
        };

        function createChart(type) {
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
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14,
                                    family: 'Poppins'
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            },
                            titleFont: { family: 'Poppins' },
                            bodyFont: { family: 'Poppins' }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                                    }
                                    if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                    }
                                    return 'Rp ' + value;
                                },
                                font: { family: 'Poppins' }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { family: 'Poppins' }
                            }
                        }
                    }
                }
            });
        }

        createChart('{{ $type }}');

        // Event listener for main filter button
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const year = filterYear.value;
            const month = filterMonth.value;
            const currentType = chartTypeSelect.value;
            window.location.href = `{{ route('admin.dashboard') }}?year=${year}&month=${month}&type=${currentType}`;
        });

        // Event listener for chart type change
        chartTypeSelect.addEventListener('change', function() {
            createChart(this.value);
        });
    });
</script>
@endpush
