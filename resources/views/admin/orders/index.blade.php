@extends('layouts.admin-layout')

@section('title', 'Konfirmasi Pesanan - Admin Kost Lolita')

@section('content')
<div class="px-6 py-6">

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Konfirmasi Pesanan</h1>
            <p class="text-gray-500 text-sm mt-1">
                Kelola dan konfirmasi pesanan kamar dari penghuni.
            </p>
        </div>
    </div>

    {{-- Filter Status --}}
    <div class="mb-4 flex flex-wrap gap-2">
        @php
            $statuses = [
                'pending'  => 'Menunggu Konfirmasi',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                'all'      => 'Semua',
            ];
        @endphp

        @foreach($statuses as $key => $label)
            <a href="{{ route('admin.orders.index', ['status' => $key]) }}"
               class="px-4 py-2 rounded-full text-sm border
                      {{ $status === $key ? 'bg-primary text-white border-primary' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Tabel Pesanan --}}
    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pemesan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kamar</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal Masuk</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Lama Sewa</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-700">
                            #{{ $order->id }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-800">{{ $order->full_name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->email }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-gray-800">
                                Kamar {{ $order->room->room_number ?? '-' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                Rp {{ number_format($order->room->price ?? 0, 0, ',', '.') }}/bln
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ optional($order->start_date)->format('d-m-Y') }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $order->rent_duration }} bln
                        </td>
                        <td class="px-4 py-3">
                            @if($order->status === 'pending')
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-[11px] font-semibold">
                                    MENUNGGU
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
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-md
                                      bg-primary text-white hover:bg-accent">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            Tidak ada pesanan untuk status ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
