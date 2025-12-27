@extends('layouts.user-layout')

@section('title', 'Dashboard Penghuni')

@section('content')
<div class="min-h-screen bg-[#F3F4F6] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-serif font-bold text-[#1F2937]">Halo, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-gray-500 mt-1">Selamat datang kembali di rumah Anda.</p>
            </div>
            
            {{-- PERBAIKAN 1: Tombol diarahkan ke Menu Kontrak Saya --}}
            <a href="{{ route('user.contract.index') }}" class="inline-flex items-center gap-2 bg-[#222831] text-[#DFD0B8] px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <i class="fa-solid fa-file-contract group-hover:scale-110 transition-transform"></i> Detail Kontrak Saya
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-[#DFD0B8] transition-colors">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kamar Anda</p>
                    <h3 class="text-2xl font-bold text-[#222831]">
                        {{ $activeOrder ? 'No. ' . $activeOrder->room->room_number : '-' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $activeOrder ? 'Sedang Dihuni' : 'Belum Ada Sewa' }}
                    </p>
                </div>
                <div class="absolute right-4 top-4 p-3 bg-blue-50 rounded-2xl text-blue-500 group-hover:bg-blue-100 transition-colors">
                    <i class="fa-solid fa-bed text-xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-[#DFD0B8] transition-colors">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sisa Waktu</p>
                    <div class="flex items-baseline gap-1">
                        <h3 class="text-2xl font-bold {{ $daysLeft < 7 && $daysLeft > 0 ? 'text-red-500' : 'text-[#222831]' }}">
                            {{ $activeOrder ? $daysLeft : '-' }}
                        </h3>
                        @if($activeOrder) <span class="text-sm font-medium text-gray-500">Hari</span> @endif
                    </div>
                    @if($activeOrder && $daysLeft < 7)
                        <p class="text-[10px] text-red-500 font-bold mt-1 bg-red-50 px-2 py-0.5 rounded inline-block">Segera Perpanjang!</p>
                    @else
                        <p class="text-xs text-gray-500 mt-1">Hingga {{ $leaseEndDate ? $leaseEndDate->format('d M Y') : '-' }}</p>
                    @endif
                </div>
                <div class="absolute right-4 top-4 p-3 bg-green-50 rounded-2xl text-green-500 group-hover:bg-green-100 transition-colors">
                    <i class="fa-regular fa-clock text-xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-[#DFD0B8] transition-colors">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Pengeluaran</p>
                    <h3 class="text-2xl font-bold text-[#222831]">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Akumulasi Pembayaran</p>
                </div>
                <div class="absolute right-4 top-4 p-3 bg-[#DFD0B8]/20 rounded-2xl text-[#bda682] group-hover:bg-[#DFD0B8]/40 transition-colors">
                    <i class="fa-solid fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">

                @if($activeOrder)
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden relative">
                        <div class="h-32 bg-gradient-to-r from-[#222831] to-[#393E46] relative">
                            <div class="absolute top-0 right-0 p-8 opacity-10">
                                <i class="fa-solid fa-building text-9xl text-white"></i>
                            </div>
                        </div>
                        
                        <div class="px-8 pb-8 -mt-12 relative z-10">
                            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h2 class="text-3xl font-serif font-bold text-[#222831]">Kamar {{ $activeOrder->room->room_number }}</h2>
                                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full border border-green-200 uppercase tracking-wide">Aktif</span>
                                    </div>
                                    <p class="text-sm text-gray-500 flex items-center gap-2">
                                        <i class="fa-regular fa-calendar"></i> Masuk: {{ \Carbon\Carbon::parse($activeOrder->start_date)->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400 font-bold uppercase mb-1">Biaya Sewa</p>
                                    <p class="text-xl font-bold text-[#222831]">Rp {{ number_format($activeOrder->room->price, 0, ',', '.') }}<span class="text-sm text-gray-400 font-normal">/bln</span></p>
                                </div>
                            </div>

                            <div class="mt-8">
                                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-[#DFD0B8] to-[#c5ac89] h-3 rounded-full transition-all duration-1000 ease-out shadow-sm" style="width: {{ $leaseProgress }}%"></div>
                                </div>
                                <div class="flex justify-between mt-2 text-xs text-gray-400">
                                    <span>Mulai: {{ \Carbon\Carbon::parse($activeOrder->start_date)->format('d M Y') }}</span>
                                    <span>Selesai: {{ $leaseEndDate ? $leaseEndDate->format('d M Y') : '-' }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
                                <a href="{{ route('user.contract.index') }}" class="py-3.5 rounded-xl bg-[#222831] text-[#DFD0B8] font-bold text-center hover:bg-black transition shadow-md flex items-center justify-center gap-2 group">
                                    <i class="fa-solid fa-file-contract group-hover:scale-110 transition-transform"></i>
                                    Perpanjang Kontrak
                                </a>
                                <a href="{{ route('user.reports.index') }}" class="py-3.5 rounded-xl border-2 border-gray-100 text-gray-600 font-bold text-center hover:border-gray-200 hover:bg-gray-50 transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-screwdriver-wrench text-gray-400"></i>
                                    Lapor Kerusakan
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-10 text-center relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-br from-[#222831] to-[#393E46] opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>
                        <div class="relative z-10">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-gray-100">
                                <i class="fa-solid fa-house-chimney text-3xl text-gray-400"></i>
                            </div>
                            <h2 class="text-2xl font-bold font-serif text-[#222831] mb-2">Anda Belum Menyewa Kamar</h2>
                            <p class="text-gray-500 text-sm mb-8 max-w-md mx-auto leading-relaxed">
                                Temukan kenyamanan tinggal di Kost Lolita. Lihat ketersediaan kamar dan mulai pengalaman hunian terbaik Anda.
                            </p>
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                        <h3 class="font-bold text-[#222831] flex items-center gap-2">
                            <i class="fa-solid fa-receipt text-gray-400"></i> Riwayat Transaksi
                        </h3>
                        <a href="{{ route('user.finance.index') }}" class="text-xs font-bold text-[#bda682] hover:text-[#222831] transition">Lihat Semua</a>
                    </div>
                    
                    <div class="divide-y divide-gray-50">
                        @forelse($recentOrders as $order)
                            <div class="p-6 hover:bg-gray-50 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    @php
                                        $iconClass = match($order->status) {
                                            'approved' => 'bg-green-50 text-green-600 border-green-100',
                                            'finished' => 'bg-gray-100 text-gray-500 border-gray-200', // Selesai = Abu-abu (Neutral)
                                            'pending'  => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                            'rejected' => 'bg-red-50 text-red-600 border-red-100',
                                            default    => 'bg-gray-50 text-gray-400 border-gray-100'
                                        };
                                        $iconSymbol = match($order->status) {
                                            'approved' => 'fa-check',
                                            'finished' => 'fa-flag-checkered', // Icon Bendera Finish untuk status finished
                                            'pending'  => 'fa-clock',
                                            'rejected' => 'fa-xmark',
                                            default    => 'fa-question'
                                        };
                                    @endphp

                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg shrink-0 border {{ $iconClass }}">
                                        <i class="fa-solid {{ $iconSymbol }}"></i>
                                    </div>

                                    <div>
                                        <p class="text-sm font-bold text-[#222831]">
                                            {{ $order->type == 'new' ? 'Sewa Baru' : ($order->type == 'extension' ? 'Perpanjangan' : 'Pindah Kamar') }}
                                            <span class="text-gray-400 font-normal">• Kamar {{ $order->room->room_number ?? '?' }}</span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-[#222831]">
                                        Rp {{ number_format(($order->room->price ?? 0) * $order->rent_duration, 0, ',', '.') }}
                                    </p>
                                    
                                    @php
                                        $badgeClass = match($order->status) {
                                            'approved' => 'text-green-600 bg-green-50 border-green-100',
                                            'finished' => 'text-gray-600 bg-gray-100 border-gray-200', // Selesai
                                            'pending'  => 'text-yellow-600 bg-yellow-50 border-yellow-100',
                                            'rejected' => 'text-red-600 bg-red-50 border-red-100',
                                            default    => 'text-gray-500 bg-gray-50 border-gray-100'
                                        };
                                        $badgeText = match($order->status) {
                                            'approved' => 'Berhasil',
                                            'finished' => 'Selesai', // Teks Selesai
                                            'pending'  => 'Menunggu',
                                            'rejected' => 'Ditolak',
                                            default    => ucfirst($order->status)
                                        };
                                    @endphp
                                    <span class="inline-block mt-1 text-[10px] font-bold px-2 py-0.5 rounded border {{ $badgeClass }}">
                                        {{ $badgeText }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-regular fa-folder-open text-2xl text-gray-300"></i>
                                </div>
                                <p class="text-sm text-gray-400">Belum ada riwayat transaksi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1 space-y-6">

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-20 bg-gray-50"></div>
                    <div class="relative z-10">
                        <img class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md mx-auto mb-4"
                             src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=222831&color=DFD0B8&size=128"
                             alt="{{ $user->name }}">
                        <h3 class="text-xl font-bold text-[#222831]">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 mb-6">{{ $user->email }}</p>
                        
                        <a href="{{ route('user.profile.edit') }}" class="block w-full py-2.5 rounded-xl border border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-50 transition">
                            Edit Profil Saya
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h4 class="font-bold text-[#222831] mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-bell text-[#DFD0B8]"></i> Status Tagihan
                    </h4>

                    @if($pendingOrders > 0)
                        <div class="p-4 bg-yellow-50 border border-yellow-100 rounded-2xl flex items-start gap-3">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center shrink-0 text-yellow-600">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-yellow-800">{{ $pendingOrders }} Tagihan Pending</p>
                                <p class="text-xs text-yellow-700 mt-1 leading-relaxed">Admin sedang memverifikasi pembayaran Anda.</p>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-green-50 border border-green-100 rounded-2xl flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center shrink-0 text-green-600">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-green-800">Semua Beres!</p>
                                <p class="text-xs text-green-600 mt-0.5">Tidak ada tagihan tertunggak.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="bg-[#222831] rounded-3xl p-8 text-center text-white relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                    
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-headset text-2xl text-[#DFD0B8]"></i>
                        </div>
                        <h4 class="font-bold text-lg mb-2 font-serif">Butuh Bantuan?</h4>
                        <p class="text-xs text-gray-400 mb-6 leading-relaxed">
                            Punya pertanyaan seputar pembayaran atau fasilitas? Kami siap membantu 24/7.
                        </p>
                        <a href="{{ route('messages.index') }}" class="block w-full py-3 bg-[#DFD0B8] text-[#222831] text-sm font-bold rounded-xl hover:bg-white transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Hubungi Admin
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection