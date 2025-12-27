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
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Manajemen Pesanan</span>
                <h1 class="text-2xl font-serif font-bold text-[#222831]">Detail Konfirmasi</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden relative">
                    <div class="absolute top-0 right-0 p-6">
                        @php
                            $statusClass = match($order->status) {
                                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                'approved' => 'bg-green-100 text-green-700 border-green-200',
                                'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                'finished' => 'bg-gray-100 text-gray-500 border-gray-200',
                                default => 'bg-gray-100 text-gray-500'
                            };
                            $statusIcon = match($order->status) {
                                'pending' => 'fa-clock',
                                'approved' => 'fa-circle-check',
                                'rejected' => 'fa-circle-xmark',
                                'finished' => 'fa-flag-checkered',
                                default => 'fa-circle'
                            };
                        @endphp
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold border {{ $statusClass }}">
                            <i class="fa-solid {{ $statusIcon }}"></i>
                            {{ ucfirst($order->status == 'pending' ? 'Menunggu Konfirmasi' : $order->status) }}
                        </span>
                    </div>

                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->full_name) }}&background=222831&color=DFD0B8"
                                 class="w-16 h-16 rounded-full border-4 border-gray-50 shadow-sm">
                            <div>
                                <h2 class="text-2xl font-bold text-[#222831]">{{ $order->full_name }}</h2>
                                <div class="flex gap-3 text-sm text-gray-500 mt-1">
                                    <span><i class="fa-solid fa-envelope mr-1"></i> {{ $order->email }}</span>
                                    <span><i class="fa-solid fa-phone mr-1"></i> {{ $order->phone }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Kamar</p>
                                <p class="text-lg font-bold text-[#222831]">No. {{ $order->room->room_number ?? '-' }}</p>
                                <p class="text-xs text-gray-500">Tipe Standard</p>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                                <p class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1">Durasi Pengajuan</p>
                                <p class="text-lg font-bold text-blue-700">+ {{ $order->rent_duration }} Bulan</p>
                                <p class="text-xs text-blue-500">
                                    @if($order->type == 'new') Penghuni Baru
                                    @elseif($order->type == 'extension') Perpanjangan
                                    @else Pindah Kamar
                                    @endif
                                </p>
                            </div>

                            <div class="bg-green-50 p-4 rounded-2xl border border-green-100">
                                <p class="text-[10px] font-bold text-green-600 uppercase tracking-wider mb-1">Tagihan</p>
                                <p class="text-lg font-bold text-green-700">Rp {{ number_format(($order->room->price ?? 0) * $order->rent_duration, 0, ',', '.') }}</p>
                                <p class="text-xs text-green-600">Lunas via Transfer</p>
                            </div>
                        </div>

                        <div class="bg-[#222831] rounded-2xl p-5 text-[#DFD0B8] flex items-center justify-between shadow-md mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                                    <i class="fa-solid fa-hourglass-half"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold opacity-60 uppercase">Total Lama Menghuni (Akumulasi)</p>
                                    <p class="text-xl font-bold text-white">
                                        {{ $totalDuration }} Bulan
                                        @if($order->status == 'pending')
                                            <span class="text-sm opacity-60 font-normal">(+ {{ $order->rent_duration }} bulan ini)</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs opacity-60">Sejak</p>
                                <p class="font-bold text-white">
                                    {{ $history->isEmpty() ? $order->start_date->format('M Y') : $history->last()->start_date->format('M Y') }}
                                </p>
                            </div>
                        </div>

                        @if($order->transfer_proof_path)
                            <div class="border-t border-gray-100 pt-6">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-3">Bukti Pembayaran</p>
                                <a href="{{ asset('storage/'.$order->transfer_proof_path) }}" target="_blank" class="group relative block w-fit overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                                    <img src="{{ asset('storage/'.$order->transfer_proof_path) }}" class="h-40 w-auto object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                        <span class="text-white text-xs font-bold bg-black/50 px-3 py-1 rounded-full">Lihat Full</span>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700">Riwayat Pesanan Sebelumnya</h3>
                        <span class="text-xs font-bold bg-white border px-2 py-1 rounded">{{ $history->count() }} Data</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="text-xs text-gray-400 uppercase bg-white border-b">
                                <tr>
                                    <th class="px-6 py-3 font-bold">Tanggal</th>
                                    <th class="px-6 py-3 font-bold">Kamar</th>
                                    <th class="px-6 py-3 font-bold">Durasi</th>
                                    <th class="px-6 py-3 font-bold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-sm">
                                @forelse($history as $hist)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3">{{ $hist->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-3">Kamar {{ $hist->room->room_number ?? '-' }}</td>
                                        <td class="px-6 py-3">{{ $hist->rent_duration }} Bulan</td>
                                        <td class="px-6 py-3">
                                            @if($hist->status == 'finished')
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded border">Selesai</span>
                                            @elseif($hist->status == 'approved')
                                                <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded border border-green-100">Aktif</span>
                                            @else
                                                <span class="text-xs text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-6 text-center text-gray-400 text-xs">Belum ada riwayat lainnya.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1" x-data="{ editMode: {{ $order->status === 'pending' ? 'true' : 'false' }} }">
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 sticky top-6">

                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                        <h3 class="font-bold text-[#222831] flex items-center gap-2">
                            <i class="fa-solid fa-gavel"></i> Konfirmasi
                        </h3>
                        <button x-show="editMode && '{{ $order->status }}' !== 'pending'"
                                @click="editMode = false"
                                type="button"
                                class="text-xs text-gray-400 hover:text-red-500 font-bold transition"
                                style="display: none;">
                            <i class="fa-solid fa-xmark"></i> Batal
                        </button>
                    </div>

                    <div x-show="!editMode" class="text-center py-6" x-transition>
                        @if($order->status == 'approved')
                            <div class="w-20 h-20 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-green-100">
                                <i class="fa-solid fa-check text-3xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 text-xl">Disetujui</h4>
                            <p class="text-xs text-gray-500 mt-2 px-4">Pesanan telah aktif. Data kamar dan keuangan sudah diperbarui.</p>
                        @elseif($order->status == 'rejected')
                            <div class="w-20 h-20 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-red-100">
                                <i class="fa-solid fa-xmark text-3xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 text-xl">Ditolak</h4>
                            <p class="text-xs text-gray-500 mt-2 px-4">Permintaan pesanan ini telah ditolak oleh admin.</p>
                        @else
                            <div class="w-20 h-20 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-gray-100">
                                <i class="fa-solid fa-flag-checkered text-3xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 text-xl">Selesai</h4>
                        @endif

                        @if($order->admin_note)
                            <div class="mt-6 bg-gray-50 p-4 rounded-xl text-left border border-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Catatan Admin</p>
                                <p class="text-sm text-gray-700 italic">"{{ $order->admin_note }}"</p>
                            </div>
                        @endif

                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <button @click="editMode = true" class="text-xs text-blue-600 font-bold hover:underline hover:text-blue-800 transition">
                                <i class="fa-solid fa-lock-open mr-1"></i> Koreksi Status
                            </button>
                        </div>
                    </div>

                    <div x-show="editMode" style="display: none;" x-transition>

                        @if($order->status != 'pending')
                            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3 mb-5 flex gap-3 items-start">
                                <i class="fa-solid fa-triangle-exclamation text-yellow-600 mt-0.5"></i>
                                <p class="text-xs text-yellow-700 leading-relaxed">
                                    <strong>Perhatian:</strong> Pesanan ini sudah diproses sebelumnya. Mengubah status dapat mempengaruhi laporan keuangan dan ketersediaan kamar.
                                </p>
                            </div>
                        @endif

                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status pesanan ini? Aksi ini akan mempengaruhi data kamar dan keuangan.');">
                            @csrf
                            @method('PATCH')

                            <div class="mb-5">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tindakan</label>
                                <div class="relative">
                                    <select name="status" class="w-full appearance-none rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 py-3 px-4 font-bold text-gray-700 cursor-pointer bg-white">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Menunggu Review</option>
                                        <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>✅ Setujui (Approve)</option>
                                        <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>❌ Tolak (Reject)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan Admin</label>
                                <textarea name="admin_note" rows="4" class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 p-3 text-sm resize-none" placeholder="Alasan disetujui/ditolak...">{{ $order->admin_note }}</textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-[#222831] text-[#DFD0B8] font-bold rounded-xl hover:bg-black hover:scale-[1.02] transition-all shadow-lg flex items-center justify-center gap-2">
                                <i class="fa-solid fa-paper-plane"></i>
                                {{ $order->status == 'pending' ? 'Simpan Keputusan' : 'Update Status' }}
                            </button>
                        </form>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100 text-xs text-gray-400 text-center">
                        <p>Pesanan dibuat pada:</p>
                        <p class="font-bold text-gray-600">{{ $order->created_at->translatedFormat('l, d F Y - H:i') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
