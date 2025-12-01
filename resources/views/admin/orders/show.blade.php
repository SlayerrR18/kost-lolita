@extends('layouts.admin-layout')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.orders.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Detail Transaksi</span>
                <h1 class="text-2xl font-serif font-bold text-[#222831]">Pesanan #{{ $order->id }}</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <div class="lg:col-span-2 space-y-6">

                @php
                    $statusClass = match($order->status) {
                        'pending' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
                        'approved' => 'bg-green-50 border-green-200 text-green-800',
                        'rejected' => 'bg-red-50 border-red-200 text-red-800',
                        default => 'bg-gray-50 border-gray-200 text-gray-800'
                    };
                    $iconClass = match($order->status) {
                        'pending' => 'fa-clock',
                        'approved' => 'fa-check-circle',
                        'rejected' => 'fa-times-circle',
                        default => 'fa-info-circle'
                    };
                @endphp

                <div class="rounded-2xl p-6 border flex items-start gap-4 shadow-sm {{ $statusClass }}">
                    <div class="p-3 rounded-full bg-white/60 shrink-0">
                        <i class="fa-solid {{ $iconClass }} text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Status: {{ ucfirst($order->status == 'pending' ? 'Menunggu Konfirmasi' : $order->status) }}</h3>
                        <p class="text-sm opacity-80 mt-1">
                            @if($order->status == 'pending') Harap periksa bukti pembayaran sebelum menyetujui.
                            @elseif($order->status == 'approved') Pesanan ini telah disetujui dan kamar telah dipesan.
                            @else Pesanan ini telah ditolak. @endif
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-[#222831]">Informasi Sewa</h3>
                        <span class="text-xs text-gray-500"><i class="fa-regular fa-calendar mr-1"></i> {{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">

                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase mb-3">Penyewa</p>
                            <div class="flex items-center gap-3 mb-4">
                                <img class="h-12 w-12 rounded-full object-cover border border-gray-200"
                                     src="https://ui-avatars.com/api/?name={{ urlencode($order->full_name) }}&background=222831&color=DFD0B8" alt="">
                                <div>
                                    <p class="font-bold text-[#222831] text-lg">{{ $order->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->email }}</p>
                                </div>
                            </div>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center gap-3">
                                    <div class="w-6 flex justify-center"><i class="fa-solid fa-phone text-gray-400"></i></div>
                                    <span>{{ $order->phone }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-6 flex justify-center"><i class="fa-solid fa-id-card text-gray-400"></i></div>
                                    <span>{{ $order->id_number }}</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-6 flex justify-center mt-0.5"><i class="fa-solid fa-map-pin text-gray-400"></i></div>
                                    <span>{{ $order->address }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase mb-3">Unit Kamar</p>
                            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                                <div class="flex justify-between items-start mb-4 pb-3 border-b border-gray-200">
                                    <span class="text-xl font-bold text-[#222831]">Kamar {{ $order->room->room_number }}</span>
                                    <span class="bg-[#222831] text-[#DFD0B8] text-[10px] uppercase font-bold px-2 py-1 rounded">Standard</span>
                                </div>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Harga/bln</span>
                                        <span class="font-medium">Rp {{ number_format($order->room->price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Durasi</span>
                                        <span class="font-medium">{{ $order->rent_duration }} Bulan</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Mulai</span>
                                        <span class="font-medium">{{ optional($order->start_date)->format('d M Y') }}</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-3 mt-1 flex justify-between items-center bg-white p-3 rounded-lg border">
                                        <span class="font-bold text-gray-700">Total</span>
                                        <span class="font-bold text-lg text-[#222831]">Rp {{ number_format($order->room->price * $order->rent_duration, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col h-full">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-id-card"></i> Foto Identitas
                        </p>
                        @if($order->id_photo_path)
                            <a href="{{ asset('storage/'.$order->id_photo_path) }}" target="_blank" class="block group relative overflow-hidden rounded-xl bg-gray-100 flex-1 min-h-[200px]">
                                <img src="{{ asset('storage/'.$order->id_photo_path) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white backdrop-blur-sm">
                                    <div class="text-center">
                                        <i class="fa-solid fa-magnifying-glass-plus text-2xl mb-2"></i>
                                        <p class="text-xs font-bold uppercase">Lihat Penuh</p>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div class="h-48 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 text-sm italic border-2 border-dashed border-gray-200">Tidak ada foto</div>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col h-full">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-receipt"></i> Bukti Pembayaran
                        </p>
                        @if($order->transfer_proof_path)
                            <a href="{{ asset('storage/'.$order->transfer_proof_path) }}" target="_blank" class="block group relative overflow-hidden rounded-xl bg-gray-100 flex-1 min-h-[200px]">
                                <img src="{{ asset('storage/'.$order->transfer_proof_path) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white backdrop-blur-sm">
                                    <div class="text-center">
                                        <i class="fa-solid fa-magnifying-glass-plus text-2xl mb-2"></i>
                                        <p class="text-xs font-bold uppercase">Lihat Penuh</p>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div class="h-48 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 text-sm italic border-2 border-dashed border-gray-200">Tidak ada bukti</div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-[#222831] mb-4 text-lg">Tindakan Admin</h3>

                    @if($order->status === 'pending')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Catatan (Opsional)</label>
                                <textarea name="note" rows="3" class="w-full border-gray-200 rounded-lg text-sm focus:ring-[#222831] focus:border-[#222831] bg-gray-50" placeholder="Contoh: Pembayaran diterima, terima kasih."></textarea>
                            </div>

                            <div class="space-y-3">
                                <button type="submit" name="status" value="approved" class="w-full flex items-center justify-center gap-2 bg-green-600 text-white py-3.5 rounded-xl font-bold hover:bg-green-700 transition shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                    <i class="fa-solid fa-check"></i> Setujui Pesanan
                                </button>
                                <button type="submit" name="status" value="rejected" class="w-full flex items-center justify-center gap-2 bg-white text-red-600 border border-red-200 py-3.5 rounded-xl font-bold hover:bg-red-50 transition">
                                    <i class="fa-solid fa-xmark"></i> Tolak Pesanan
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <div class="w-14 h-14 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-500">
                                <i class="fa-solid fa-lock text-xl"></i>
                            </div>
                            <p class="text-gray-900 font-bold">Pesanan Selesai</p>
                            <p class="text-xs text-gray-500 mt-1 px-4">Status pesanan ini sudah final dan tidak dapat diubah lagi.</p>

                            @if($order->admin_note)
                                <div class="mt-6 text-left px-4 pt-4 border-t border-gray-200">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Catatan Admin:</p>
                                    <p class="text-sm text-gray-700 mt-1 italic">"{{ $order->admin_note }}"</p>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
