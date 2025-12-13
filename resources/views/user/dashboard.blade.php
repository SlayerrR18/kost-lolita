@extends('layouts.user-layout')

@section('title', 'Dashboard Penghuni')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-serif font-bold text-[#222831]">Halo, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-gray-500 mt-1 text-sm">Selamat datang di dashboard penghuni Kost Lolita.</p>
            </div>
            <div>
                <a href="{{ route('landing') }}#rooms" class="inline-flex items-center gap-2 bg-[#222831] text-[#DFD0B8] px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari Kamar Baru
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">

                @if($activeOrder)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative group">
                        <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                            <i class="fa-solid fa-bed text-9xl text-[#222831]"></i>
                        </div>

                        <div class="p-8">
                            <div class="flex flex-col md:flex-row justify-between items-start mb-6 gap-4">
                                <div>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200 mb-3">
                                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Sedang Dihuni
                                    </span>
                                    <h2 class="text-4xl font-serif font-bold text-[#222831]">Kamar {{ $activeOrder->room->room_number }}</h2>
                                    <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
                                        <i class="fa-regular fa-envelope"></i> {{ $user->email }}
                                    </div>
                                </div>

                                <div class="md:text-right">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sisa Masa Sewa</p>
                                    <div class="flex items-baseline md:justify-end gap-1 mt-1">
                                        <span class="text-4xl font-bold font-mono {{ $daysLeft < 7 ? 'text-red-500' : 'text-[#222831]' }}">{{ $daysLeft }}</span>
                                        <span class="text-sm font-medium text-gray-500">Hari</span>
                                    </div>
                                    @if($daysLeft < 7)
                                        <p class="text-xs text-red-500 font-bold mt-1 bg-red-50 px-2 py-1 rounded inline-block">Segera perpanjang!</p>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-2xl p-6 grid grid-cols-1 sm:grid-cols-3 gap-6 border border-gray-100">
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase mb-1 flex items-center gap-1"><i class="fa-solid fa-right-to-bracket"></i> Tanggal Masuk</p>
                                    <p class="font-semibold text-[#222831] text-lg">{{ \Carbon\Carbon::parse($activeOrder->start_date)->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase mb-1 flex items-center gap-1"><i class="fa-solid fa-right-from-bracket"></i> Tanggal Keluar</p>
                                    <p class="font-semibold text-[#222831] text-lg">{{ $leaseEndDate ? $leaseEndDate->format('d M Y') : '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase mb-1 flex items-center gap-1"><i class="fa-solid fa-tag"></i> Biaya / Bulan</p>
                                    <p class="font-semibold text-[#222831] text-lg">Rp {{ number_format($activeOrder->room->price, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                <button class="flex-1 py-3 rounded-xl bg-[#DFD0B8] text-[#222831] font-bold hover:bg-[#d4c2a3] transition shadow-sm flex items-center justify-center gap-2">
                                    <a href="{{ route('user.contract.index') }}">
                                        <i class="fa-solid fa-file-invoice"></i> Lihat Detail Kontrak
                                    </a>
                                </button>
                                <button class="flex-1 px-6 py-3 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition flex items-center justify-center gap-2">
                                    <a href="{{ route('user.reports.index') }}">
                                        <i class="fa-solid fa-file-circle-exclamation"></i> Laporkan Masalah
                                    </a>
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-[#222831] rounded-3xl shadow-lg p-8 text-white text-center relative overflow-hidden">
                        <div class="relative z-10 py-8">
                            <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                                <i class="fa-solid fa-house-user text-3xl text-[#DFD0B8]"></i>
                            </div>
                            <h2 class="text-2xl font-bold font-serif mb-2">Anda Belum Menyewa Kamar</h2>
                            <p class="text-gray-400 text-sm mb-6 max-w-md mx-auto">
                                Temukan kenyamanan tinggal di Kost Lolita. Lihat ketersediaan kamar dan booking sekarang juga.
                            </p>
                            <a href="{{ route('landing') }}#rooms" class="inline-block bg-[#DFD0B8] text-[#222831] px-8 py-3 rounded-xl font-bold hover:bg-white transition transform hover:scale-105 shadow-lg">
                                Pilih Kamar Sekarang
                            </a>
                        </div>
                        <div class="absolute top-0 left-0 w-64 h-64 bg-[#DFD0B8] rounded-full blur-[100px] opacity-10"></div>
                    </div>
                @endif

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-[#222831] text-lg">Riwayat Transaksi</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 font-bold">ID & Tanggal</th>
                                    <th class="px-6 py-4 font-bold">Detail Kamar</th>
                                    <th class="px-6 py-4 font-bold">Total</th>
                                    <th class="px-6 py-4 font-bold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentOrders as $order)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <span class="block text-sm font-bold text-[#222831]">#{{ $order->id }}</span>
                                            <span class="text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-800 font-medium">Kamar {{ $order->room->room_number ?? '?' }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->rent_duration }} Bulan</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-bold text-[#222831]">
                                                Rp {{ number_format(($order->room->price ?? 0) * $order->rent_duration, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($order->status === 'pending')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                                    <i class="fa-solid fa-clock"></i> Proses
                                                </span>
                                            @elseif($order->status === 'approved')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                                    <i class="fa-solid fa-check"></i> Berhasil
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                                    <i class="fa-solid fa-xmark"></i> Gagal
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                            Belum ada riwayat transaksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 text-center">
                    <img class="h-20 w-20 rounded-full object-cover border-4 border-gray-50 mx-auto mb-4"
                         src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=222831&color=DFD0B8&size=128"
                         alt="{{ $user->name }}">
                    <h3 class="text-lg font-bold text-[#222831]">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $user->email }}</p>
                    <a href="{{ route('profile.edit') }}" class="text-sm font-bold text-[#DFD0B8] hover:text-[#222831] hover:underline transition">
                        Edit Profil
                    </a>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-red-50 text-red-500 rounded-lg">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase">Total Pengeluaran</span>
                    </div>
                    <h3 class="text-2xl font-bold text-[#222831]">
                        Rp {{ number_format($totalSpent, 0, ',', '.') }}
                    </h3>
                    <p class="text-xs text-gray-400 mt-1">Akumulasi sewa kamar</p>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-blue-50 text-blue-500 rounded-lg">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase">Status Tagihan</span>
                    </div>

                    @if($pendingOrders > 0)
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-circle-exclamation text-yellow-600 mt-1"></i>
                                <div>
                                    <p class="text-sm font-bold text-yellow-800">{{ $pendingOrders }} Pesanan Pending</p>
                                    <p class="text-xs text-yellow-700 mt-1">Mohon tunggu konfirmasi admin.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3 text-green-600">
                            <i class="fa-solid fa-check-circle text-xl"></i>
                            <span class="text-sm font-bold">Tidak ada tagihan pending.</span>
                        </div>
                    @endif
                </div>

                <div class="bg-[#DFD0B8]/20 rounded-3xl p-6 border border-[#DFD0B8]/30">
                    <h4 class="font-bold text-[#222831] mb-2">Butuh Bantuan?</h4>
                    <p class="text-xs text-gray-600 mb-4">Hubungi admin jika ada kerusakan fasilitas atau pertanyaan pembayaran.</p>
                    <a href="https://wa.me/6281234567890" target="_blank" class="block w-full py-2.5 bg-[#222831] text-white text-center text-sm font-bold rounded-xl hover:bg-black transition shadow-md">
                        <i class="fa-brands fa-whatsapp mr-1"></i> Hubungi Admin
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
