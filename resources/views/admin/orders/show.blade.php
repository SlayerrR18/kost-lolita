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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">

                <div class="rounded-2xl p-6 border flex items-start gap-4 shadow-sm
                    @if($order->status == 'pending') bg-yellow-50 border-yellow-200 text-yellow-800
                    @elseif($order->status == 'approved') bg-green-50 border-green-200 text-green-800
                    @else bg-red-50 border-red-200 text-red-800 @endif">

                    <div class="p-3 rounded-full bg-white/60">
                        @if($order->status == 'pending') <i class="fa-solid fa-clock text-xl"></i>
                        @elseif($order->status == 'approved') <i class="fa-solid fa-check-circle text-xl"></i>
                        @else <i class="fa-solid fa-times-circle text-xl"></i> @endif
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
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">

                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase mb-2">Penyewa</p>
                            <div class="flex items-center gap-3 mb-3">
                                <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($order->full_name) }}&background=222831&color=DFD0B8" alt="">
                                <div>
                                    <p class="font-bold text-[#222831]">{{ $order->full_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->email }}</p>
                                </div>
                            </div>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p><i class="fa-solid fa-phone w-5 text-gray-400"></i> {{ $order->phone }}</p>
                                <p><i class="fa-solid fa-id-card w-5 text-gray-400"></i> {{ $order->id_number }}</p>
                                <p><i class="fa-solid fa-map-pin w-5 text-gray-400"></i> {{ $order->address }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase mb-2">Unit Kamar</p>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-lg font-bold text-[#222831]">Kamar {{ $order->room->room_number }}</span>
                                    <span class="bg-[#222831] text-[#DFD0B8] text-xs px-2 py-1 rounded">Standard</span>
                                </div>
                                <div class="space-y-2 text-sm">
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
                                    <div class="border-t border-gray-200 pt-2 mt-2 flex justify-between items-center">
                                        <span class="font-bold text-gray-700">Total</span>
                                        <span class="font-bold text-lg text-[#222831]">Rp {{ number_format($order->room->price * $order->rent_duration, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3">Foto Identitas (KTP)</p>
                        @if($order->id_photo_path)
                            <a href="{{ asset('storage/'.$order->id_photo_path) }}" target="_blank" class="block group relative overflow-hidden rounded-xl bg-gray-100">
                                <img src="{{ asset('storage/'.$order->id_photo_path) }}" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                                </div>
                            </a>
                        @else
                            <div class="h-48 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 text-sm italic">Tidak ada foto</div>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3">Bukti Pembayaran</p>
                        @if($order->transfer_proof_path)
                            <a href="{{ asset('storage/'.$order->transfer_proof_path) }}" target="_blank" class="block group relative overflow-hidden rounded-xl bg-gray-100">
                                <img src="{{ asset('storage/'.$order->transfer_proof_path) }}" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                                </div>
                            </a>
                        @else
                            <div class="h-48 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 text-sm italic">Tidak ada bukti</div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-8">
                    <h3 class="font-bold text-[#222831] mb-4">Tindakan Admin</h3>

                    @if($order->status === 'pending')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Catatan (Opsional)</label>
                                <textarea name="note" rows="3" class="w-full border-gray-200 rounded-lg text-sm focus:ring-[#222831] focus:border-[#222831]" placeholder="Contoh: Pembayaran diterima, terima kasih."></textarea>
                            </div>

                            <div class="space-y-3">
                                <button type="submit" name="status" value="approved" class="w-full flex items-center justify-center gap-2 bg-green-600 text-white py-3 rounded-xl font-bold hover:bg-green-700 transition shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                    <i class="fa-solid fa-check"></i> Setujui Pesanan
                                </button>
                                <button type="submit" name="status" value="rejected" class="w-full flex items-center justify-center gap-2 bg-white text-red-600 border border-red-200 py-3 rounded-xl font-bold hover:bg-red-50 transition">
                                    <i class="fa-solid fa-xmark"></i> Tolak Pesanan
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-6 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-500">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">Pesanan ini telah diproses.</p>
                            @if($order->admin_note)
                                <div class="mt-4 text-left px-4">
                                    <p class="text-xs font-bold text-gray-400 uppercase">Catatan Admin:</p>
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
