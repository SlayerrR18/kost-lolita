@extends('layouts.user-layout')

@section('title', 'Laporan Keuangan')

@push('css')
<style>
    /* Styling khusus untuk halaman ini */
    .finance-card-gradient {
        background: linear-gradient(135deg, #222831 0%, #393E46 100%);
    }
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #e5e7eb;
        border-radius: 10px;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-serif font-bold text-[#222831]">Laporan Keuangan</h1>
                <p class="text-gray-500 text-sm mt-1">Ringkasan pengeluaran dan riwayat pembayaran sewa Anda.</p>
            </div>

            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-print"></i> Cetak Laporan
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            <div class="lg:col-span-2 finance-card-gradient rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10 pointer-events-none">
                    <i class="fa-solid fa-wallet text-9xl text-white"></i>
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div>
                        <p class="text-[#DFD0B8] text-sm font-bold uppercase tracking-wider mb-1">Total Pengeluaran</p>
                        <h2 class="text-4xl font-bold font-mono">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
                        <p class="text-xs text-gray-400 mt-2">Akumulasi pembayaran yang telah disetujui</p>
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/10 flex items-center gap-4">
                        <div class="flex-1">
                            <div class="flex justify-between text-xs text-gray-400 mb-2">
                                <span>Status Tagihan Saat Ini</span>
                                <span class="{{ $transaksiTerakhir && $transaksiTerakhir->status == 'pending' ? 'text-yellow-400' : 'text-green-400' }}">
                                    {{ $transaksiTerakhir && $transaksiTerakhir->status == 'pending' ? 'Menunggu Konfirmasi' : 'Aman / Lunas' }}
                                </span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-2">
                                <div class="h-2 rounded-full {{ $transaksiTerakhir && $transaksiTerakhir->status == 'pending' ? 'bg-yellow-400' : 'bg-green-400' }}" style="width: {{ $transaksiTerakhir && $transaksiTerakhir->status == 'pending' ? '50%' : '100%' }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-[#222831] mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-chart-simple text-[#DFD0B8]"></i> Ringkasan Aktivitas
                    </h3>

                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-600">Total Transaksi</span>
                            </div>
                            <span class="text-lg font-bold text-[#222831]">{{ $transactions->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-600">Terakhir Bayar</span>
                            </div>
                            <div class="text-right">
                                @if($transaksiTerakhir)
                                    <p class="text-sm font-bold text-[#222831]">{{ $transaksiTerakhir->created_at->format('d M Y') }}</p>
                                @else
                                    <p class="text-sm text-gray-400">-</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-xs text-gray-400 text-center">
                        Butuh bantuan soal tagihan? <a href="#" class="text-[#222831] font-bold hover:underline">Hubungi Admin</a>
                    </p>
                </div>
            </div>

        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/50">
                <div>
                    <h3 class="font-bold text-lg text-[#222831]">Riwayat Pembayaran</h3>
                    <p class="text-xs text-gray-500 mt-1">Menampilkan {{ $transactions->count() }} data transaksi terbaru</p>
                </div>
                {{-- <div class="flex gap-2">
                    <select class="text-xs border-gray-300 rounded-lg focus:ring-[#222831]">
                        <option>Semua Status</option>
                        <option>Lunas</option>
                        <option>Pending</option>
                    </select>
                </div> --}}
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-white border-b border-gray-100 text-gray-400 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-8 py-5 font-bold">ID Transaksi</th>
                            <th class="px-8 py-5 font-bold">Tanggal & Waktu</th>
                            <th class="px-8 py-5 font-bold">Keterangan</th>
                            <th class="px-8 py-5 font-bold">Nominal</th>
                            <th class="px-8 py-5 font-bold text-center">Status</th>
                            <th class="px-8 py-5 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($transactions as $trx)
                            @php
                                $nominal = optional($trx->room)->price * $trx->rent_duration;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-8 py-5">
                                    <span class="font-mono text-sm font-bold text-[#222831]">#ORD-{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-700">{{ $trx->created_at->translatedFormat('d M Y') }}</span>
                                        <span class="text-xs text-gray-400">{{ $trx->created_at->format('H:i') }} WIB</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-800">Sewa Kamar {{ optional($trx->room)->room_number }}</span>
                                        <span class="text-xs text-gray-500">Durasi: {{ $trx->rent_duration }} Bulan</span>
                                    </div>
                                    @if($trx->type == 'extension')
                                        <span class="inline-block mt-1 text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded border border-blue-100 font-bold uppercase tracking-wide">Perpanjangan</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-sm font-bold text-[#222831]">Rp {{ number_format($nominal, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($trx->status == 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                            <i class="fa-solid fa-check"></i> Lunas
                                        </span>
                                    @elseif($trx->status == 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                            <i class="fa-solid fa-clock"></i> Proses
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                            <i class="fa-solid fa-xmark"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('user.orders.show', $trx->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-400 hover:text-[#DFD0B8] hover:bg-[#222831] hover:border-[#222831] transition-all shadow-sm group-hover:shadow-md"
                                       title="Lihat Detail">
                                        <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fa-solid fa-receipt text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada riwayat transaksi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="px-8 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
