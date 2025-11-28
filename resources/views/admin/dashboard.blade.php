@extends('layouts.admin-layout')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-3xl font-serif font-bold text-[#222831]">Dashboard Overview</h2>
                <p class="text-gray-500 mt-1 text-sm">Selamat datang kembali, {{ Auth::user()->name }}! Berikut ringkasan kost Anda.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button class="bg-[#222831] text-[#DFD0B8] px-5 py-2.5 rounded-lg text-sm font-medium shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                    <i class="fa-solid fa-download"></i> Unduh Laporan
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Kamar</p>
                        <h3 class="text-3xl font-bold text-[#222831] mt-2">{{ $totalrooms }}</h3>
                    </div>
                    <div class="p-3 bg-[#DFD0B8]/20 rounded-xl text-[#222831] group-hover:bg-[#25477a] group-hover:text-[#DFD0B8] transition-colors">
                        <i class="fa-solid fa-bed text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-500 flex items-center font-medium">
                        <i class="fa-solid fa-arrow-trend-up mr-1"></i> +2
                    </span>
                    <span class="text-gray-400 ml-2">Bulan ini</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tersedia</p>
                        <h3 class="text-3xl font-bold text-[#222831] mt-2">{{ $availablerooms }}</h3>
                    </div>
                    <div class="p-3 bg-green-50 rounded-xl text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-door-open text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-green-500 h-1.5 rounded-full" style="width: 25%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">25% dari total kamar</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Penghuni Aktif</p>
                        <h3 class="text-3xl font-bold text-[#222831] mt-2">{{ $activeusers }}</h3>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex -space-x-2 overflow-hidden">
                    <div class="w-8 h-8 rounded-full border-2 border-white shadow-sm bg-cover bg-center" style="background-image: url('https://i.pravatar.cc/150?img=1');"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white shadow-sm bg-cover bg-center" style="background-image: url('https://i.pravatar.cc/150?img=2');"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white shadow-sm bg-cover bg-center" style="background-image: url('https://i.pravatar.cc/150?img=3');"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white shadow-sm bg-cover bg-center" style="background-image: url('https://i.pravatar.cc/150?img=4');"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white shadow-sm bg-cover bg-center flex items-center justify-center text-xs font-medium text-gray-700 bg-gray-200">+{{ $activeusers - 4 }}</div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#222831] to-[#1a1f26] p-6 rounded-2xl shadow-lg text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keuntungan (Bln)</p>
                        <h3 class="text-2xl font-bold text-[#DFD0B8] mt-2">Rp 12.5 Jt</h3>
                    </div>
                    <div class="p-2 bg-white/10 rounded-lg text-[#DFD0B8]">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-400">
                    <span class="text-green-400 font-bold"><i class="fa-solid fa-caret-up"></i> 8.2%</span> vs bulan lalu
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-[#222831] font-serif">Analisis Keuangan Tahunan</h3>
                    <select class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#222831] focus:border-[#222831] block p-2">
                        <option>Tahun 2025</option>
                        <option>Tahun 2024</option>
                    </select>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="financeChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-[#222831] font-serif mb-6">Distribusi Pengeluaran</h3>
                <div class="relative h-64 flex justify-center">
                    <canvas id="expenseChart"></canvas>
                </div>
                <div class="mt-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#222831]"></span> Listrik & Air</span>
                        <span class="font-bold text-gray-700">45%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#DFD0B8]"></span> Maintenance</span>
                        <span class="font-bold text-gray-700">30%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-gray-400"></span> Gaji Staff</span>
                        <span class="font-bold text-gray-700">25%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-[#222831] font-serif">Pembayaran Terbaru</h3>
                <a href="#" class="text-sm text-[#222831] hover:text-[#DFD0B8] font-medium">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4">Penghuni</th>
                            <th scope="col" class="px-6 py-4">Kamar</th>
                            <th scope="col" class="px-6 py-4">Tanggal</th>
                            <th scope="col" class="px-6 py-4">Jumlah</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-[#222831] text-white flex items-center justify-center text-xs">AD</div>
                                Andi Pratama
                            </td>
                            <td class="px-6 py-4">A-101</td>
                            <td class="px-6 py-4">25 Nov 2025</td>
                            <td class="px-6 py-4 font-bold text-[#222831]">Rp 1.500.000</td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Lunas</span>
                            </td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-[#DFD0B8] text-[#222831] flex items-center justify-center text-xs">BP</div>
                                Budi Santoso
                            </td>
                            <td class="px-6 py-4">B-205</td>
                            <td class="px-6 py-4">24 Nov 2025</td>
                            <td class="px-6 py-4 font-bold text-[#222831]">Rp 1.200.000</td>
                            <td class="px-6 py-4">
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-200">Pending</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // 1. Chart Keuangan (Bar Chart - Double)
        const ctxFinance = document.getElementById('financeChart').getContext('2d');
        new Chart(ctxFinance, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: [12, 19, 15, 17, 22, 24, 25, 22, 30, 28, 15, 0], // Data Dummy
                        backgroundColor: '#222831', // Dark Slate
                        borderRadius: 4,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Pengeluaran',
                        data: [8, 10, 8, 12, 10, 15, 12, 10, 18, 12, 10, 0], // Data Dummy
                        backgroundColor: '#DFD0B8', // Beige
                        borderRadius: 4,
                        barPercentage: 0.6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 2] }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // 2. Chart Pengeluaran (Doughnut)
        const ctxExpense = document.getElementById('expenseChart').getContext('2d');
        new Chart(ctxExpense, {
            type: 'doughnut',
            data: {
                labels: ['Listrik & Air', 'Maintenance', 'Gaji Staff'],
                datasets: [{
                    data: [45, 30, 25],
                    backgroundColor: [
                        '#222831', // Dark
                        '#DFD0B8', // Beige
                        '#9CA3AF'  // Gray
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false } // Legend kita buat manual di HTML biar rapi
                },
                cutout: '75%' // Membuat donat lebih tipis
            }
        });
    });
</script>
@endsection
