@extends('layouts.admin-layout')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-serif font-bold text-[#222831]">Dashboard Overview</h2>
                <p class="text-gray-500 mt-1 text-sm">
                    Ringkasan data untuk periode
                    <span class="font-bold text-[#222831]">{{ Carbon\Carbon::create()->month($selectedMonth)->translatedFormat('F') }} {{ $selectedYear }}</span>
                </p>
            </div>

            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <select name="month" class="bg-white border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#222831] focus:border-[#222831] p-2.5 shadow-sm" onchange="this.form.submit()">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="year" class="bg-white border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#222831] focus:border-[#222831] p-2.5 shadow-sm" onchange="this.form.submit()">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>

                <div class="bg-[#222831] text-[#DFD0B8] px-4 py-2.5 rounded-lg shadow-md">
                    <i class="fa-solid fa-filter"></i>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Kamar</p>
                        <h3 class="text-3xl font-bold text-[#222831] mt-2">{{ $totalrooms }}</h3>
                    </div>
                    <div class="p-3 bg-[#DFD0B8]/20 rounded-xl text-[#222831]">
                        <i class="fa-solid fa-bed text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-500">
                    <span class="text-[#222831] font-bold">{{ $availablerooms }}</span> kamar tersedia saat ini
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-md transition-all">
                @php
                    $filledRooms = $totalrooms - $availablerooms;
                    $occupancy = $totalrooms > 0 ? round(($filledRooms / $totalrooms) * 100) : 0;

                    // Tentukan Warna & Status
                    if($occupancy >= 80) {
                        $occColor = 'bg-green-500';
                        $occText = 'text-green-600';
                        $occStatus = 'Sangat Baik';
                        $occIcon = 'fa-circle-check';
                    } elseif($occupancy >= 50) {
                        $occColor = 'bg-yellow-500';
                        $occText = 'text-yellow-600';
                        $occStatus = 'Normal';
                        $occIcon = 'fa-circle-exclamation';
                    } else {
                        $occColor = 'bg-red-500';
                        $occText = 'text-red-600';
                        $occStatus = 'Perlu Promosi';
                        $occIcon = 'fa-triangle-exclamation';
                    }
                @endphp

                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tingkat Hunian</p>
                        <h3 class="text-3xl font-bold text-[#222831] mt-2">{{ $occupancy }}%</h3>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                        <i class="fa-solid fa-chart-pie text-xl"></i>
                    </div>
                </div>

                <div class="mt-4 w-full bg-gray-100 rounded-full h-2 relative overflow-hidden">
                    <div class="{{ $occColor }} h-2 rounded-full transition-all duration-1000" style="width: {{ $occupancy }}%"></div>
                </div>

                <div class="mt-3 flex justify-between items-center text-xs">
                    <span class="text-gray-500 font-medium">
                        <strong>{{ $filledRooms }}</strong> dari <strong>{{ $totalrooms }}</strong> kamar terisi
                    </span>
                    <span class="{{ $occText }} font-bold flex items-center gap-1">
                        <i class="fa-solid {{ $occIcon }}"></i> {{ $occStatus }}
                    </span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Penghuni Aktif</p>
                        <h3 class="text-3xl font-bold text-[#222831] mt-2">{{ $activeusers }}</h3>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-xl text-purple-600">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex -space-x-2 overflow-hidden">
                    @foreach($activeUserSamples as $user)
                        <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white object-cover"
                             src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=222831&color=DFD0B8"
                             alt="{{ $user->name }}" title="{{ $user->name }}">
                    @endforeach
                    @if($activeusers > 5)
                        <div class="h-8 w-8 rounded-full ring-2 ring-white bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">+{{ $activeusers - 5 }}</div>
                    @endif
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#222831] to-[#1a1f26] p-6 rounded-2xl shadow-lg text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Profit ({{ Carbon\Carbon::create()->month($selectedMonth)->format('M') }})</p>
                        <h3 class="text-2xl font-bold text-[#DFD0B8] mt-2">
                            Rp {{ number_format($currentProfit, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="p-2 bg-white/10 rounded-lg text-[#DFD0B8]">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-400 flex items-center">
                    @if($profitTrend == 'up')
                        <span class="text-green-400 font-bold flex items-center"><i class="fa-solid fa-arrow-trend-up mr-1"></i> {{ round($profitPercentage, 1) }}%</span>
                    @elseif($profitTrend == 'down')
                        <span class="text-red-400 font-bold flex items-center"><i class="fa-solid fa-arrow-trend-down mr-1"></i> {{ round(abs($profitPercentage), 1) }}%</span>
                    @else
                        <span class="text-gray-400 font-bold">-</span>
                    @endif
                    <span class="ml-2">dari bulan lalu</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-[#222831] font-serif">Arus Kas Tahun {{ $selectedYear }}</h3>
                        <p class="text-xs text-gray-400">Perbandingan Pemasukan vs Pengeluaran</p>
                    </div>
                    <div class="flex gap-4 text-xs font-semibold">
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#222831]"></span> Masuk</div>
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#DFD0B8]"></span> Keluar</div>
                    </div>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="financeChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                <h3 class="text-lg font-bold text-[#222831] font-serif mb-2">Distribusi Pengeluaran</h3>
                <p class="text-xs text-gray-400 mb-6">Tahun {{ $selectedYear }}</p>

                <div class="relative h-48 flex justify-center mb-6">
                    <canvas id="expenseChart"></canvas>
                </div>

                <div class="space-y-4 flex-1 overflow-y-auto pr-2 custom-scrollbar max-h-48">
                    @forelse($topExpenseCategories as $category)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $category->color }};"></span>
                                <span class="text-sm font-medium text-gray-700 capitalize">{{ $category->category }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-[#222831]">{{ $category->formatted_total }}</p>
                                <p class="text-[10px] text-gray-400">{{ $category->percentage }}%</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 text-sm py-4">Belum ada data pengeluaran.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="text-lg font-bold text-[#222831] font-serif">Transaksi Terbaru</h3>
                    <p class="text-xs text-gray-400">5 pembayaran terakhir yang diterima</p>
                </div>
                <a href="{{ route('admin.finance.income.index') }}" class="text-sm text-[#222831] hover:text-[#DFD0B8] font-bold underline decoration-2 decoration-[#DFD0B8]/50">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-white border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Penghuni</th>
                            <th class="px-6 py-4 font-semibold">Kamar</th>
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Jumlah</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentPayments as $p)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#222831] text-[#DFD0B8] flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr(data_get($p, 'order.user.name', 'Guest'), 0, 2)) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ data_get($p, 'order.user.name', 'Guest') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ data_get($p, 'order.room.room_number', '-') }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ \Carbon\Carbon::parse($p->date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-green-600">
                                + Rp {{ number_format($p->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded-full border border-green-200">
                                    BERHASIL
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">Belum ada transaksi terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Data Dinamis dari Controller
        const incomeData = @json($monthlyIncomes ?? []);
        const expenseData = @json($monthlyExpenses ?? []);

        // 1. Chart Arus Kas (Bar)
        const ctxFinance = document.getElementById('financeChart').getContext('2d');
        new Chart(ctxFinance, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: incomeData,
                        backgroundColor: '#222831',
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Pengeluaran',
                        data: expenseData,
                        backgroundColor: '#DFD0B8',
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }, // Legend custom di HTML
                    tooltip: {
                        backgroundColor: '#222831',
                        titleColor: '#DFD0B8',
                        bodyFont: { size: 13 },
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f3f4f6' },
                        ticks: { font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });

        // 2. Chart Distribusi (Doughnut)
        const expenseLabels = @json($expenseLabels ?? []);
        const expenseValues = @json($expenseValues ?? []);
        const colors = ['#222831', '#DFD0B8', '#9CA3AF', '#60A5FA', '#F97316', '#10B981'];

        const ctxExpense = document.getElementById('expenseChart').getContext('2d');

        if(expenseValues.length > 0) {
            new Chart(ctxExpense, {
                type: 'doughnut',
                data: {
                    labels: expenseLabels,
                    datasets: [{
                        data: expenseValues,
                        backgroundColor: colors,
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) { label += ': '; }
                                    let value = context.raw;
                                    label += 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    return label;
                                }
                            }
                        }
                    },
                    cutout: '75%'
                }
            });
        } else {
            // Tampilkan placeholder jika kosong
            ctxExpense.font = "14px Inter";
            ctxExpense.fillStyle = "#9CA3AF";
            ctxExpense.textAlign = "center";
            ctxExpense.fillText("Belum ada data", 150, 75);
        }
    });
</script>
@endsection
