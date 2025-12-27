@extends('layouts.admin-layout')

@section('title', 'Profil Penghuni - ' . $user->name)

@section('content')
<div x-data="{ activeTab: 'profile', deleteModalOpen: false, showKtpModal: false }" class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.tenants.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Manajemen Penghuni</span>
                <h1 class="text-2xl font-serif font-bold text-[#222831]">Profil Penghuni</h1>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 mb-8 overflow-hidden relative">
            <div class="h-32 bg-gradient-to-r from-[#222831] to-[#393E46]"></div>

            <div class="px-8 pb-8">
                <div class="flex flex-col md:flex-row items-start md:items-end -mt-12 gap-6">

                    <div class="relative">
                        <img class="h-28 w-28 rounded-2xl object-cover border-4 border-white shadow-xl bg-white"
                             src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=DFD0B8&color=222831&size=128&bold=true"
                             alt="{{ $user->name }}">
                        <div class="absolute -bottom-2 -right-2 w-6 h-6 rounded-full border-4 border-white {{ $user->room ? 'bg-green-500' : 'bg-gray-400' }}"
                             title="{{ $user->room ? 'Penghuni Aktif' : 'Tidak Menyewa' }}"></div>
                    </div>

                    <div class="flex-1 pt-2 md:pt-0">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                            <div>
                                <h2 class="text-3xl font-bold text-[#222831]">{{ $user->name }}</h2>
                                <p class="text-gray-500 font-medium flex items-center gap-2">
                                    <i class="fa-regular fa-envelope"></i> {{ $user->email }}
                                </p>
                            </div>

                            <div class="flex gap-3">
                                <a href="{{ route('admin.tenants.edit', $user) }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 font-bold hover:bg-gray-50 transition shadow-sm flex items-center gap-2 text-sm">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <button @click="deleteModalOpen = true" class="px-5 py-2.5 rounded-xl bg-red-50 text-red-600 border border-red-100 font-bold hover:bg-red-100 transition shadow-sm flex items-center gap-2 text-sm">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mt-8 pt-6 border-t border-gray-100">

                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Status Sewa</p>
                        @if($user->room)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                Non-Aktif
                            </span>
                        @endif
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Kamar Saat Ini</p>
                        <p class="text-lg font-bold text-[#222831]">
                            {{ $user->room ? 'No. ' . $user->room->room_number : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Bergabung Sejak</p>
                        <p class="text-sm font-bold text-gray-600">{{ $user->created_at->format('d M Y') }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Bergabung Hingga</p>
                        <p class="text-sm font-bold text-gray-600">
                            @php
                                // Ambil tanggal berakhir dari order aktif terakhir
                                $activeOrder = $orders->where('status', 'approved')->sortByDesc('end_date')->first();
                            @endphp
                            {{ $activeOrder ? $activeOrder->end_date->format('d M Y') : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Transaksi</p>
                        <p class="text-lg font-bold text-[#222831]">{{ $orders->count() }}x</p>
                    </div>

                </div>
            </div>
        </div>

        <div class="mb-6 border-b border-gray-200">
            <nav class="flex gap-8" aria-label="Tabs">
                <button @click="activeTab = 'profile'"
                        class="pb-4 text-sm font-bold border-b-2 transition-all duration-200 flex items-center gap-2"
                        :class="activeTab === 'profile' ? 'border-[#222831] text-[#222831]' : 'border-transparent text-gray-400 hover:text-gray-600 hover:border-gray-300'">
                    <i class="fa-regular fa-id-badge"></i> Biodata & Dokumen
                </button>
                <button @click="activeTab = 'history'"
                        class="pb-4 text-sm font-bold border-b-2 transition-all duration-200 flex items-center gap-2"
                        :class="activeTab === 'history' ? 'border-[#222831] text-[#222831]' : 'border-transparent text-gray-400 hover:text-gray-600 hover:border-gray-300'">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Sewa
                </button>
            </nav>
        </div>

        <div class="min-h-[400px]">

            <div x-show="activeTab === 'profile'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h3 class="font-serif font-bold text-xl text-[#222831] mb-6 pb-4 border-b border-gray-100">Informasi Pribadi</h3>

                        @php
                            // Ambil data dari order terakhir yang approved (data paling update)
                            $lastOrder = $user->orders()->latest()->first();
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-400 uppercase">Nama Lengkap</label>
                                <p class="text-gray-800 font-medium">{{ $user->name }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-400 uppercase">Email</label>
                                <p class="text-gray-800 font-medium">{{ $user->email }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-400 uppercase">Nomor Telepon</label>
                                <p class="text-gray-800 font-medium">{{ $lastOrder->phone ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-400 uppercase">Nomor Identitas (NIK)</label>
                                <p class="text-gray-800 font-medium">{{ $lastOrder->id_number ?? '-' }}</p>
                            </div>
                            <div class="md:col-span-2 space-y-1">
                                <label class="text-xs font-bold text-gray-400 uppercase">Alamat Asal</label>
                                <p class="text-gray-800 font-medium">{{ $lastOrder->address ?? '-' }}</p>
                            </div>

                            @if(isset($user->emergency_contact) && is_array($user->emergency_contact))
                                <div class="md:col-span-2 mt-4 pt-6 border-t border-dashed border-gray-200">
                                    <h4 class="text-sm font-bold text-red-500 mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-kit-medical"></i> Kontak Darurat
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="text-xs font-bold text-gray-400 uppercase">Nama Kontak</label>
                                            <p class="text-gray-800">{{ $user->emergency_contact['name'] ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-gray-400 uppercase">Nomor Kontak</label>
                                            <p class="text-gray-800">{{ $user->emergency_contact['phone'] ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sticky top-6">
                            <h3 class="font-serif font-bold text-xl text-[#222831] mb-6">Kartu Identitas</h3>

                            @php
                                $ktpPath = $lastOrder->id_photo_path ?? null;
                            @endphp

                            @if($ktpPath)
                                <div class="group relative rounded-2xl overflow-hidden cursor-pointer shadow-md hover:shadow-xl transition-all" @click="showKtpModal = true">
                                    <img src="{{ asset('storage/' . $ktpPath) }}" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="text-white text-xs font-bold bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/50">
                                            <i class="fa-solid fa-eye mr-1"></i> Lihat
                                        </span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-3 text-center">Data KTP dari pesanan terakhir.</p>
                            @else
                                <div class="h-48 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400">
                                    <i class="fa-regular fa-id-card text-3xl mb-2 opacity-50"></i>
                                    <span class="text-xs font-medium">Dokumen tidak tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'history'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;">

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">ID</th>
                                    <th class="px-6 py-4">Tanggal</th>
                                    <th class="px-6 py-4">Kamar</th>
                                    <th class="px-6 py-4">Tipe</th>
                                    <th class="px-6 py-4">Durasi</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-mono text-xs text-[#222831]">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4 text-sm font-bold">
                                            {{ $order->room->room_number ?? 'Hapus' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($order->type == 'new')
                                                <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-1 rounded border border-blue-100 font-bold">Baru</span>
                                            @elseif($order->type == 'extension')
                                                <span class="text-[10px] bg-purple-50 text-purple-600 px-2 py-1 rounded border border-purple-100 font-bold">Perpanjang</span>
                                            @else
                                                <span class="text-[10px] bg-orange-50 text-orange-600 px-2 py-1 rounded border border-orange-100 font-bold">Pindah</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm">{{ $order->rent_duration }} Bulan</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusStyle = match($order->status) {
                                                    'approved' => 'bg-green-100 text-green-700 border-green-200',
                                                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                                    'finished' => 'bg-gray-100 text-gray-500 border-gray-200',
                                                    default => 'bg-gray-100 text-gray-500'
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusStyle }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:bg-[#222831] hover:text-white transition">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                            <i class="fa-regular fa-folder-open text-3xl mb-2"></i>
                                            <p class="text-sm">Belum ada riwayat transaksi.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-[99] overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div @click.away="deleteModalOpen = false" class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 text-center overflow-hidden">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Penghuni?</h3>
                <p class="text-gray-500 mb-6">Tindakan ini akan menghapus akun <strong>{{ $user->name }}</strong> beserta seluruh riwayat sewanya secara permanen.</p>
                <div class="flex gap-3 justify-center">
                    <button @click="deleteModalOpen = false" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">Batal</button>
                    <form action="{{ route('admin.tenants.destroy', $user) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 shadow-md transition">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showKtpModal" style="display: none;" class="fixed inset-0 z-[100] overflow-hidden" x-cloak>
        <div class="absolute inset-0 bg-black/95 backdrop-blur-sm transition-opacity" @click="showKtpModal = false"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div class="relative max-w-4xl w-full pointer-events-auto" x-show="showKtpModal" x-transition.scale>
                <button @click="showKtpModal = false" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                @if(isset($ktpPath))
                    <img src="{{ asset('storage/' . $ktpPath) }}" class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl">
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
