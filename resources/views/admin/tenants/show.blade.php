@extends('layouts.admin-layout')

@section('title', 'Profil Penghuni - ' . $user->name)

@section('content')
<div x-data="{ activeTab: 'profile', deleteModalOpen: false, showKtpModal: false }" class="py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.tenants.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Manajemen Penghuni</span>
                <h1 class="text-2xl font-serif font-bold text-[#222831]">Detail Profil</h1>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                <i class="fa-solid fa-user-circle text-9xl text-[#222831]"></i>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
                <div class="shrink-0 relative">
                    <img class="h-28 w-28 rounded-full object-cover border-4 border-white shadow-lg"
                         src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=222831&color=DFD0B8&size=128"
                         alt="{{ $user->name }}">
                    <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-500 border-2 border-white rounded-full" title="Status Aktif"></div>
                </div>

                <div class="flex-1 text-center md:text-left space-y-2">
                    <div>
                        <h2 class="text-3xl font-bold text-[#222831]">{{ $user->name }}</h2>
                        <p class="text-gray-500 font-medium">{{ $user->email }}</p>
                    </div>

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                            Aktif
                        </span>
                        <span class="text-xs text-gray-400 border-l border-gray-300 pl-3">
                            Bergabung {{ $user->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <a href="{{ route('admin.tenants.edit', $user) }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-700 font-bold hover:bg-gray-50 hover:border-gray-300 transition shadow-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <button @click="deleteModalOpen = true" class="px-6 py-3 rounded-xl bg-red-50 text-red-600 border border-red-100 font-bold hover:bg-red-100 transition shadow-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8 pt-8 border-t border-gray-100">
                <div class="bg-gray-50 rounded-2xl p-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#222831] flex items-center justify-center text-[#DFD0B8] text-xl">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Total Transaksi</p>
                        <p class="text-lg font-bold text-gray-800">{{ $orders->count() }}</p>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#DFD0B8] flex items-center justify-center text-[#222831] text-xl">
                        <i class="fa-solid fa-bed"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Kamar Saat Ini</p>
                            <p class="text-lg font-bold text-gray-800">
                            {{ optional(optional(optional($orders->where('status', 'approved'))->last())->room)->room_number ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="flex gap-8 border-b border-gray-200 mb-8 overflow-x-auto">
                <button @click="activeTab = 'profile'"
                        class="pb-4 text-sm font-bold border-b-2 transition-all duration-200 whitespace-nowrap px-2"
                        :class="activeTab === 'profile' ? 'border-[#222831] text-[#222831]' : 'border-transparent text-gray-400 hover:text-gray-600'">
                    <i class="fa-regular fa-id-card mr-2"></i> Biodata & Dokumen
                </button>
                <button @click="activeTab = 'history'"
                        class="pb-4 text-sm font-bold border-b-2 transition-all duration-200 whitespace-nowrap px-2"
                        :class="activeTab === 'history' ? 'border-[#222831] text-[#222831]' : 'border-transparent text-gray-400 hover:text-gray-600'">
                    <i class="fa-solid fa-clock-rotate-left mr-2"></i> Riwayat Sewa
                </button>
            </div>

            <div x-show="activeTab === 'profile'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-bold text-[#222831] mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-[#DFD0B8]"></i> Informasi Pribadi
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-8 gap-x-8">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Nama Lengkap</p>
                                <p class="font-medium text-gray-800 border-b border-gray-100 pb-2">{{ $user->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Email</p>
                                <p class="font-medium text-gray-800 border-b border-gray-100 pb-2">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Nomor Telepon</p>
                                <p class="font-medium text-gray-800 border-b border-gray-100 pb-2">{{ optional(optional(optional($user->room)->orders)->last())->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Nomor Identitas (NIK)</p>
                                <p class="font-medium text-gray-800 border-b border-gray-100 pb-2">{{ optional(optional(optional($user->room)->orders)->last())->id_number ?? '-' }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Alamat Asal</p>
                                <p class="font-medium text-gray-800 border-b border-gray-100 pb-2">{{ optional(optional(optional($user->room)->orders)->last())->address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 h-fit">
                        <h3 class="text-lg font-bold text-[#222831] mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-[#DFD0B8]"></i> Dokumen Identitas
                        </h3>

                        @php
                            $firstOrder = $orders->first();
                            // Prioritas: Kolom user -> Kolom order pertama
                            $ktpPath = $user->id_photo_path ?? ($firstOrder->id_photo_path ?? null);
                        @endphp

                        @if($ktpPath)
                            <div class="relative group cursor-pointer overflow-hidden rounded-2xl border border-gray-200" @click="showKtpModal = true">
                                <img src="{{ asset('storage/' . $ktpPath) }}" alt="KTP {{ $user->name }}" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-[#222831]/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                                    <i class="fa-solid fa-magnifying-glass-plus text-2xl mb-2"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider">Lihat Detail</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-3 text-center">Klik gambar untuk memperbesar</p>
                        @else
                            <div class="h-48 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400">
                                <i class="fa-regular fa-image text-3xl mb-2 opacity-50"></i>
                                <span class="text-xs font-medium">Belum ada foto KTP</span>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            <div x-show="activeTab === 'history'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;">

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    @if($orders->isEmpty())
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-regular fa-folder-open text-3xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada riwayat pesanan.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500">
                                    <tr>
                                        <th class="px-6 py-4 text-xs font-bold uppercase">ID Order</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase">Kamar</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase">Tanggal</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase">Status</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-sm font-mono font-bold text-[#222831]">#{{ $order->id }}</td>
                                            <td class="px-6 py-4 text-sm">Kamar {{ $order->room->room_number ?? '?' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                            <td class="px-6 py-4">
                                                @if($order->status === 'pending')
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">Menunggu</span>
                                                @elseif($order->status === 'approved')
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Disetujui</span>
                                                @else
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Ditolak</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline text-sm font-medium">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <div x-show="deleteModalOpen"
         style="display: none;"
         class="fixed inset-0 z-[99] overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" x-show="deleteModalOpen" x-transition.opacity></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div x-show="deleteModalOpen" @click.away="deleteModalOpen = false" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-serif font-bold text-[#222831]">Hapus Akun Penghuni?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Anda yakin ingin menghapus akun <strong>{{ $user->name }}</strong>? <br>Data riwayat sewa akan dihapus permanen.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <form action="{{ route('admin.tenants.destroy', $user) }}" method="POST" class="w-full sm:w-auto">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors">Ya, Hapus Akun</button>
                    </form>
                    <button @click="deleteModalOpen = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showKtpModal"
         style="display: none;"
         class="fixed inset-0 z-[100] overflow-y-auto"
         x-cloak>
        <div class="fixed inset-0 bg-black/90 backdrop-blur-sm transition-opacity" @click="showKtpModal = false" x-show="showKtpModal" x-transition.opacity></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="showKtpModal" x-transition.scale class="relative max-w-4xl w-full">
                <button @click="showKtpModal = false" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                @if($ktpPath)
                    <img src="{{ asset('storage/' . $ktpPath) }}" class="w-full h-auto rounded-lg shadow-2xl">
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
