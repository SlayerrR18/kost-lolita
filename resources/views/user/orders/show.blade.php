@extends('layouts.order')

@section('title', 'Status Pesanan - Kost Lolita')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- LOGIC STATUS WARNA & ICON --}}
    @php
        $statusColors = [
            'pending'  => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'icon' => 'fa-clock', 'title' => 'Menunggu Konfirmasi', 'desc' => 'Admin sedang memverifikasi pembayaran Anda.'],
            'approved' => ['bg' => 'bg-green-50',  'text' => 'text-green-700',  'border' => 'border-green-200',  'icon' => 'fa-circle-check', 'title' => 'Pesanan Disetujui', 'desc' => 'Selamat! Kamar berhasil dibooking.'],
            'rejected' => ['bg' => 'bg-red-50',    'text' => 'text-red-700',    'border' => 'border-red-200',    'icon' => 'fa-circle-xmark', 'title' => 'Pesanan Ditolak', 'desc' => 'Mohon hubungi admin untuk info lebih lanjut.'],
        ];
        $currentStatus = $statusColors[$order->status] ?? $statusColors['pending'];
    @endphp

    <div class="rounded-3xl shadow-sm border {{ $currentStatus['border'] }} {{ $currentStatus['bg'] }} p-8 text-center mb-8">
        <div class="w-16 h-16 {{ $currentStatus['bg'] }} border-4 border-white shadow-sm rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid {{ $currentStatus['icon'] }} text-3xl {{ $currentStatus['text'] }}"></i>
        </div>
        <h1 class="text-2xl font-serif font-bold {{ $currentStatus['text'] }}">
            {{ $currentStatus['title'] }}
        </h1>
        <p class="text-sm {{ $currentStatus['text'] }} opacity-80 mt-1">
            {{ $currentStatus['desc'] }}
        </p>
    </div>

    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden relative">

        <div class="absolute top-0 left-0 w-full h-2 bg-[#222831]"></div>

        <div class="p-8">
            <div class="flex justify-between items-start mb-8 border-b border-dashed border-gray-200 pb-6">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">ID Pesanan</p>
                    <p class="text-xl font-mono font-bold text-[#222831]">#{{ $order->id }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tanggal Order</p>
                    <p class="text-sm font-medium text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-[#222831] flex items-center gap-2">
                        <i class="fa-solid fa-door-open text-[#DFD0B8]"></i> Detail Kamar
                    </h3>
                    <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                        <div>
                            <p class="text-xs text-gray-400">Unit Kamar</p>
                            <p class="font-semibold text-gray-800">Kamar {{ $order->room->room_number ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Durasi Sewa</p>
                            <p class="font-semibold text-gray-800">{{ $order->rent_duration }} Bulan</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Tanggal Masuk</p>
                            <p class="font-semibold text-gray-800">{{ optional($order->start_date)->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-[#222831] flex items-center gap-2">
                        <i class="fa-solid fa-user text-[#DFD0B8]"></i> Data Penyewa
                    </h3>
                    <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                        <div>
                            <p class="text-xs text-gray-400">Nama Lengkap</p>
                            <p class="font-semibold text-gray-800">{{ $order->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Kontak</p>
                            <p class="font-semibold text-gray-800">{{ $order->phone }}</p>
                            <p class="text-xs text-gray-500">{{ $order->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#222831] rounded-xl p-5 flex justify-between items-center text-white">
                <span class="text-sm opacity-80">Total Pembayaran</span>
                <span class="text-xl font-bold font-serif text-[#DFD0B8]">
                    Rp {{ number_format(($order->room->price ?? 0) * $order->rent_duration, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="bg-gray-50 p-6 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-xs text-gray-500 text-center sm:text-left">
                <p>Butuh bantuan?</p>
                <a href="https://wa.me/6281234567890" target="_blank" class="text-green-600 font-bold hover:underline flex items-center gap-1 justify-center sm:justify-start">
                    <i class="fa-brands fa-whatsapp"></i> Chat Admin
                </a>
            </div>

            <div class="flex gap-3">
                <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                    <i class="fa-solid fa-print mr-1"></i> Cetak
                </button>

                @if($order->status === 'approved')
                    <a href="{{ route('user.dashboard') }}" class="px-5 py-2 bg-[#222831] text-[#DFD0B8] rounded-lg text-sm font-bold hover:shadow-lg transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('landing') }}" class="px-5 py-2 bg-[#222831] text-[#DFD0B8] rounded-lg text-sm font-bold hover:shadow-lg transition">
                        Kembali
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
