@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Pesanan Saya</h1>
        <p class="text-gray-600 text-sm mt-1">
            Riwayat pemesanan kamar di Kost Lolita.
        </p>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white border border-gray-100 rounded-xl p-6 text-center text-gray-500">
            Anda belum memiliki pesanan kamar.
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Nomor Pesanan</p>
                        <p class="font-semibold text-gray-800">
                            #{{ $order->id }} · Kamar {{ $order->room->room_number ?? '-' }}
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Tanggal masuk:
                            {{ optional($order->start_date)->format('d-m-Y') ?? '-' }}
                            · Lama sewa: {{ $order->rent_duration }} bulan
                        </p>

                        <p class="text-xs mt-1">
                            Status:
                            @if($order->status === 'pending')
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-[11px] font-semibold">
                                    MENUNGGU KONFIRMASI
                                </span>
                            @elseif($order->status === 'approved')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-[11px] font-semibold">
                                    DISETUJUI
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-[11px] font-semibold">
                                    DITOLAK
                                </span>
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <a href="{{ route('user.orders.show', $order) }}"
                           class="px-4 py-2 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-accent">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
