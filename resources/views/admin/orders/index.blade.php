@extends('layouts.admin-layout')

@section('title', 'Konfirmasi Pesanan - Admin Kost Lolita')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-serif font-bold text-[#222831]">Konfirmasi Pesanan</h1>
                <p class="text-gray-500 text-sm mt-1">
                    Pantau dan kelola pesanan masuk dari calon penghuni.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-4">
                <div class="bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                    <span class="text-xs text-gray-400 uppercase tracking-wider block">Pending</span>
                    <span class="text-xl font-bold text-yellow-600">{{ $orders->where('status', 'pending')->count() }}</span>
                </div>
            </div>
        </div>

        <div class="mb-6 border-b border-gray-200">
            <nav class="flex gap-6 overflow-x-auto" aria-label="Tabs">
                @php
                    $statuses = [
                        'all'      => ['label' => 'Semua', 'icon' => 'fa-list'],
                        'pending'  => ['label' => 'Menunggu', 'icon' => 'fa-clock'],
                        'approved' => ['label' => 'Disetujui', 'icon' => 'fa-check-circle'],
                        'rejected' => ['label' => 'Ditolak', 'icon' => 'fa-times-circle'],
                    ];
                @endphp

                @foreach($statuses as $key => $data)
                    <a href="{{ route('admin.orders.index', ['status' => $key]) }}"
                       class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 whitespace-nowrap gap-2
                       {{ $status === $key
                          ? 'border-[#222831] text-[#222831]'
                          : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fa-solid {{ $data['icon'] }} {{ $status === $key ? 'text-[#DFD0B8]' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        {{ $data['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#222831] text-[#DFD0B8]">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider rounded-tl-3xl">ID & Tanggal</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Pemesan</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Info Kamar</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right rounded-tr-3xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-6 py-4">
                                    <span class="block text-sm font-bold text-[#222831] font-mono">#{{ $order->id }}</span>
                                    <span class="text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img class="h-9 w-9 rounded-full object-cover border border-gray-200"
                                             src="https://ui-avatars.com/api/?name={{ urlencode($order->full_name) }}&background=222831&color=DFD0B8&size=128"
                                             alt="{{ $order->full_name }}">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $order->full_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->phone }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-[#222831]">Kamar {{ $order->room->room_number ?? '?' }}</div>
                                    <div class="text-xs text-gray-500">Rp {{ number_format($order->room->price ?? 0, 0, ',', '.') }}</div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $order->rent_duration }} Bulan
                                    </span>
                                    <div class="text-[10px] text-gray-400 mt-1">
                                        Masuk: {{ optional($order->start_date)->format('d/m/y') }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($order->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                            Menunggu
                                        </span>
                                    @elseif($order->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                            <i class="fa-solid fa-xmark text-[10px]"></i>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-700 hover:bg-[#222831] hover:text-[#DFD0B8] hover:border-[#222831] transition-all shadow-sm group-hover:shadow-md">
                                        Review
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fa-regular fa-folder-open text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada pesanan pada status ini.</p>
                                        <p class="text-xs text-gray-400 mt-1">Data pesanan baru akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator && $orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $orders->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
