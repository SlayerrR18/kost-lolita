@extends('layouts.user-layout')

@section('title', 'Laporan Keuangan')

@push('css')
<style>
    .finance-card-gradient {
        background: linear-gradient(135deg, #222831 0%, #393E46 100%);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-serif font-bold text-[#222831]">Laporan Keuangan</h1>
                <p class="text-gray-500 text-sm mt-1">Ringkasan pengeluaran dan riwayat pembayaran sewa.</p>
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
                        <p class="text-xs text-gray-400 mt-2">Akumulasi pembayaran yang telah disetujui.</p>
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/10">
                        <div class="flex justify-between text-xs text-gray-400 mb-2">
                            <span>Status Transaksi Terakhir</span>
                            
                            @if($transaksiTerakhir)
                                @if($transaksiTerakhir->status == 'approved' || $transaksiTerakhir->status == 'finished')
                                    <span class="text-green-400 font-bold">Lunas / Aman</span>
                                @elseif($transaksiTerakhir->status == 'pending')
                                    <span class="text-yellow-400 font-bold">Menunggu Konfirmasi</span>
                                @elseif($transaksiTerakhir->status == 'rejected')
                                    <span class="text-red-400 font-bold">Ditolak / Bermasalah</span>
                                @endif
                            @else
                                <span>-</span>
                            @endif
                        </div>
                        
                        <div class="w-full bg-white/10 rounded-full h-2">
                            @php
                                $barColor = 'bg-gray-500';
                                $width = '0%';
                                if($transaksiTerakhir) {
                                    if(in_array($transaksiTerakhir->status, ['approved', 'finished'])) {
                                        $barColor = 'bg-green-400'; $width = '100%';
                                    } elseif($transaksiTerakhir->status == 'pending') {
                                        $barColor = 'bg-yellow-400'; $width = '50%';
                                    } else {
                                        $barColor = 'bg-red-400'; $width = '100%';
                                    }
                                }
                            @endphp
                            <div class="h-2 rounded-full {{ $barColor }}" style="width: {{ $width }}"></div>
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
                            <span class="text-lg font-bold text-[#222831]">{{ $transactions->total() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-600">Terakhir Update</span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-[#222831]">
                                    {{ $transaksiTerakhir ? $transaksiTerakhir->updated_at->format('d M Y') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-lg text-[#222831]">Riwayat Pembayaran</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-white border-b border-gray-100 text-gray-400 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-8 py-5 font-bold">ID Transaksi</th>
                            <th class="px-8 py-5 font-bold">Tanggal</th>
                            <th class="px-8 py-5 font-bold">Keterangan</th>
                            <th class="px-8 py-5 font-bold">Nominal</th>
                            <th class="px-8 py-5 font-bold text-center">Status</th>
                            <th class="px-8 py-5 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($transactions as $trx)
                            @php
                                $nominal = ($trx->room->price ?? 0) * $trx->rent_duration;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-8 py-5 font-mono text-sm font-bold text-[#222831]">
                                    #INV-{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-600">
                                    {{ $trx->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-800">Sewa Kamar {{ optional($trx->room)->room_number }}</span>
                                        <span class="text-xs text-gray-500">Durasi: {{ $trx->rent_duration }} Bulan</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-sm font-bold text-[#222831]">Rp {{ number_format($nominal, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($trx->status == 'approved' || $trx->status == 'finished')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                            <i class="fa-solid fa-check"></i> Berhasil
                                        </span>
                                    @elseif($trx->status == 'pending')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                            <i class="fa-solid fa-clock"></i> Proses
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                            <i class="fa-solid fa-xmark"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    {{-- LINK KE ROUTE BARU --}}
                                    <a href="{{ route('user.finance.show', $trx->id) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-400 hover:text-[#222831] hover:bg-gray-100 transition shadow-sm"
                                       title="Lihat Invoice">
                                        <i class="fa-solid fa-file-invoice"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center text-gray-400">
                                    Belum ada riwayat transaksi.
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