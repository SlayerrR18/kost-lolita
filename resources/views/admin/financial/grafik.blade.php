@extends('layouts.main')

@section('title', 'Grafik Keuangan')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Grafik Keuangan</h1>

        <!-- Filter Form -->
        <div class="d-flex gap-3">
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

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Total Pemasukan</h6>
                            <h4 class="mb-0 text-success">Rp {{ number_format($yearlyTotals['income'], 0, ',', '.') }}</h4>
                            <small class="text-muted">Tahun {{ date('Y') }}</small>
                        </div>
                        <div class="ms-3">
                            <span class="bg-success bg-opacity-10 p-2 rounded">
                                <i data-feather="trending-up" class="text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Total Pengeluaran</h6>
                            <h4 class="mb-0 text-danger">Rp {{ number_format($yearlyTotals['expense'], 0, ',', '.') }}</h4>
                            <small class="text-muted">Tahun {{ date('Y') }}</small>
                        </div>
                        <div class="ms-3">
                            <span class="bg-danger bg-opacity-10 p-2 rounded">
                                <i data-feather="trending-down" class="text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Total Profit</h6>
                            <h4 class="mb-0 {{ $yearlyTotals['profit'] >= 0 ? 'text-primary' : 'text-danger' }}">
                                Rp {{ number_format($yearlyTotals['profit'], 0, ',', '.') }}
                            </h4>
                            <small class="text-muted">Tahun {{ date('Y') }}</small>
                        </div>
                        <div class="ms-3">
                            <span class="bg-primary bg-opacity-10 p-2 rounded">
                                <i data-feather="dollar-sign" class="text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Grafik Keuangan {{ $month ? date('F', mktime(0, 0, 0, $month, 1)) : 'Bulanan' }} {{ $year }}</h5>
                    <canvas id="monthlyChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
.card {
    border-radius: 15px;
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
@endsection
