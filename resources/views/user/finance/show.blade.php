@extends('layouts.user-layout')

@section('title', 'Detail Transaksi #INV-' . str_pad($order->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-4 mb-8 print:hidden">
            <a href="{{ route('user.finance.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#222831]">Detail Pembayaran</h1>
                <p class="text-xs text-gray-500">Lihat detail kwitansi transaksi Anda</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-100 relative print:shadow-none print:border-2">
            
            <div class="bg-[#222831] px-8 py-6 flex justify-between items-center text-white print:bg-gray-800">
                <div>
                    <h2 class="font-serif text-2xl font-bold text-[#DFD0B8]">KWITANSI</h2>
                    <p class="text-xs text-gray-400 mt-1">#INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Tanggal</p>
                    <p class="font-bold">{{ $order->created_at->format('d F Y') }}</p>
                </div>
            </div>

            <div class="p-8">
                <div class="flex justify-center mb-8">
                    @if($order->status == 'approved' || $order->status == 'finished')
                        <div class="px-6 py-2 bg-green-50 text-green-700 rounded-full border border-green-200 font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                            <i class="fa-solid fa-circle-check"></i> Pembayaran Berhasil
                        </div>
                    @elseif($order->status == 'pending')
                        <div class="px-6 py-2 bg-yellow-50 text-yellow-700 rounded-full border border-yellow-200 font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                            <i class="fa-solid fa-clock"></i> Menunggu Konfirmasi
                        </div>
                    @else
                        <div class="px-6 py-2 bg-red-50 text-red-700 rounded-full border border-red-200 font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                            <i class="fa-solid fa-circle-xmark"></i> Transaksi Ditolak
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-8 mb-8 border-b border-gray-100 pb-8">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-2">Penyewa</p>
                        <p class="text-gray-800 font-bold text-lg">{{ $order->full_name }}</p>
                        <p class="text-gray-500 text-sm">{{ $order->phone }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400 uppercase font-bold mb-2">Penerima</p>
                        <p class="text-gray-800 font-bold text-lg">Kost Lolita</p>
                        <p class="text-gray-500 text-sm">Admin Pengelola</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 mb-8 border border-gray-100">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-4">Rincian Pembayaran</p>
                    
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <p class="text-gray-800 font-bold">Sewa Kamar {{ optional($order->room)->room_number }}</p>
                            <p class="text-xs text-gray-500">{{ $order->type == 'new' ? 'Penghuni Baru' : ($order->type == 'extension' ? 'Perpanjangan' : 'Pindah Kamar') }}</p>
                        </div>
                        <p class="text-gray-800 font-medium">
                            {{ $order->rent_duration }} Bulan
                        </p>
                    </div>

                    <div class="flex justify-between items-center mb-3">
                        <p class="text-gray-600 text-sm">Harga per Bulan</p>
                        <p class="text-gray-800 text-sm">Rp {{ number_format(optional($order->room)->price, 0, ',', '.') }}</p>
                    </div>

                    <div class="border-t border-gray-200 my-4"></div>

                    <div class="flex justify-between items-center">
                        <p class="text-[#222831] font-bold text-lg">Total Bayar</p>
                        <p class="text-[#222831] font-bold text-xl font-mono">
                            Rp {{ number_format(optional($order->room)->price * $order->rent_duration, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                @if($order->transfer_proof_path)
                    <div class="mb-8 print:hidden">
                        <p class="text-xs text-gray-400 uppercase font-bold mb-3">Bukti Transfer Anda</p>
                        <div class="w-full h-48 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 flex items-center justify-center relative group">
                            <img src="{{ asset('storage/' . $order->transfer_proof_path) }}" class="h-full object-contain">
                            <a href="{{ asset('storage/' . $order->transfer_proof_path) }}" target="_blank" class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition font-bold text-sm">
                                <i class="fa-solid fa-magnifying-glass mr-2"></i> Lihat Gambar Penuh
                            </a>
                        </div>
                    </div>
                @endif

                @if($order->admin_note)
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-8">
                        <p class="text-xs text-blue-500 uppercase font-bold mb-1">Catatan Admin</p>
                        <p class="text-blue-800 text-sm italic">"{{ $order->admin_note }}"</p>
                    </div>
                @endif

            </div>

            <div class="bg-gray-50 px-8 py-6 border-t border-gray-100 text-center print:bg-white">
                <p class="text-xs text-gray-400 mb-4">Terima kasih telah melakukan pembayaran tepat waktu.</p>
                <button onclick="window.print()" class="px-6 py-2 bg-[#222831] text-[#DFD0B8] rounded-full text-sm font-bold hover:shadow-lg transition print:hidden">
                    <i class="fa-solid fa-print mr-2"></i> Cetak / Simpan PDF
                </button>
            </div>
        </div>

    </div>
</div>
@endsection