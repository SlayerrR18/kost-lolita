@extends('layouts.admin-layout')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="py-8" x-data="{
    actionStatus: '{{ $order->status }}',
    showConfirmModal: false,
    submitForm() {
        this.$refs.actionForm.submit();
    }
}">
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
                            $statusConfig = match($order->status) {
                                'pending' => ['class' => 'bg-yellow-50 text-yellow-700 border-yellow-200', 'icon' => 'fa-clock', 'text' => 'Menunggu'],
                                'approved' => ['class' => 'bg-green-50 text-green-700 border-green-200', 'icon' => 'fa-circle-check', 'text' => 'Disetujui'],
                                'rejected' => ['class' => 'bg-red-50 text-red-700 border-red-200', 'icon' => 'fa-circle-xmark', 'text' => 'Ditolak'],
                                'finished' => ['class' => 'bg-gray-100 text-gray-500 border-gray-200', 'icon' => 'fa-flag-checkered', 'text' => 'Selesai'],
                                default => ['class' => 'bg-gray-50 text-gray-500', 'icon' => 'fa-circle', 'text' => $order->status]
                            };
                        @endphp
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold border {{ $statusConfig['class'] }}">
                            <i class="fa-solid {{ $statusConfig['icon'] }}"></i> {{ strtoupper($statusConfig['text']) }}
                        </span>
                    </div>

                    <div class="p-8">
                        <div class="flex items-center gap-5 mb-8">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->full_name) }}&background=222831&color=DFD0B8"
                                 class="w-20 h-20 rounded-2xl border-4 border-gray-50 shadow-md">
                            <div>
                                <h2 class="text-3xl font-serif font-bold text-[#222831]">{{ $order->full_name }}</h2>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mt-2">
                                    <span class="flex items-center gap-2 bg-gray-50 px-3 py-1 rounded-lg border border-gray-100">
                                        <i class="fa-solid fa-envelope text-gray-400"></i> {{ $order->email }}
                                    </span>
                                    <span class="flex items-center gap-2 bg-gray-50 px-3 py-1 rounded-lg border border-gray-100">
                                        <i class="fa-solid fa-phone text-gray-400"></i> {{ $order->phone }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Kamar</p>
                                <p class="text-xl font-bold text-[#222831]">No. {{ $order->room->room_number ?? '-' }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100">
                                <p class="text-[10px] text-blue-400 font-bold uppercase tracking-wider mb-1">Durasi</p>
                                <p class="text-xl font-bold text-blue-700">{{ $order->rent_duration }} Bulan</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-green-50 border border-green-100 col-span-2 md:col-span-1">
                                <p class="text-[10px] text-green-600 font-bold uppercase tracking-wider mb-1">Total Tagihan</p>
                                <p class="text-xl font-bold text-green-700">Rp {{ number_format(($order->room->price ?? 0) * $order->rent_duration, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if($order->transfer_proof_path)
                            <div class="border-t border-gray-100 pt-6">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-4">Bukti Pembayaran Masuk</p>
                                <a href="{{ asset('storage/'.$order->transfer_proof_path) }}" target="_blank" class="block group relative overflow-hidden rounded-2xl border border-gray-200 shadow-sm max-w-sm">
                                    <img src="{{ asset('storage/'.$order->transfer_proof_path) }}" class="w-full h-auto object-cover max-h-64 transition-transform duration-700 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                        <span class="bg-white/20 backdrop-blur-md text-white border border-white/50 px-4 py-2 rounded-full text-sm font-bold">
                                            <i class="fa-solid fa-magnifying-glass-plus mr-2"></i> Perbesar Gambar
                                        </span>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-gray-400"></i> Riwayat User Ini
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="text-xs text-gray-400 uppercase border-b border-gray-100">
                                <tr>
                                    <th class="pb-3">Tanggal</th>
                                    <th class="pb-3">Kamar</th>
                                    <th class="pb-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($history as $hist)
                                    <tr>
                                        <td class="py-3 text-gray-600">{{ $hist->created_at->format('d M Y') }}</td>
                                        <td class="py-3 font-medium">Kamar {{ $hist->room->room_number ?? '-' }}</td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 rounded text-[10px] font-bold
                                                {{ $hist->status == 'approved' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                                {{ ucfirst($hist->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="py-4 text-center text-gray-400 text-xs">Tidak ada riwayat lain.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 sticky top-6">
                    <h3 class="font-bold text-[#222831] mb-6 flex items-center gap-2 text-lg">
                        <i class="fa-solid fa-gavel text-[#DFD0B8]"></i> Tindakan Admin
                    </h3>

                    <form x-ref="actionForm" action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-3 mb-6">
                            <label class="text-xs font-bold text-gray-400 uppercase">Pilih Keputusan:</label>

                            <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 group"
                                :class="actionStatus === 'approved' ? 'border-green-500 bg-green-50/50' : 'border-gray-100 hover:border-green-200 hover:bg-gray-50'">
                                <input type="radio" name="status" value="approved" class="hidden" x-model="actionStatus">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 transition-colors"
                                     :class="actionStatus === 'approved' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-green-100 group-hover:text-green-500'">
                                    <i class="fa-solid fa-check text-lg"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-gray-800" :class="actionStatus === 'approved' ? 'text-green-700' : ''">Terima Pesanan</span>
                                    <span class="text-xs text-gray-500">Kamar menjadi terisi & Pemasukan dicatat.</span>
                                </div>
                                <div x-show="actionStatus === 'approved'" class="absolute top-3 right-3 text-green-500">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 group"
                                :class="actionStatus === 'rejected' ? 'border-red-500 bg-red-50/50' : 'border-gray-100 hover:border-red-200 hover:bg-gray-50'">
                                <input type="radio" name="status" value="rejected" class="hidden" x-model="actionStatus">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 transition-colors"
                                     :class="actionStatus === 'rejected' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-red-100 group-hover:text-red-500'">
                                    <i class="fa-solid fa-xmark text-lg"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-gray-800" :class="actionStatus === 'rejected' ? 'text-red-700' : ''">Tolak Pesanan</span>
                                    <span class="text-xs text-gray-500">Kamar tetap tersedia.</span>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 group"
                                :class="actionStatus === 'pending' ? 'border-yellow-500 bg-yellow-50/50' : 'border-gray-100 hover:border-yellow-200 hover:bg-gray-50'">
                                <input type="radio" name="status" value="pending" class="hidden" x-model="actionStatus">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 transition-colors"
                                     :class="actionStatus === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-yellow-100 group-hover:text-yellow-500'">
                                    <i class="fa-solid fa-pause text-lg"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-gray-800" :class="actionStatus === 'pending' ? 'text-yellow-700' : ''">Tunda / Review</span>
                                    <span class="text-xs text-gray-500">Belum ada keputusan.</span>
                                </div>
                            </label>
                        </div>

                        <div x-show="actionStatus === 'approved'" x-transition class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                            <p class="text-xs font-bold text-green-800 uppercase mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-calculator"></i> Estimasi Pemasukan
                            </p>
                            <div class="flex justify-between text-sm text-green-700">
                                <span>Harga x Durasi:</span>
                                <span class="font-bold">Rp {{ number_format(($order->room->price ?? 0) * $order->rent_duration, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan (Opsional)</label>
                            <textarea name="admin_note" rows="3" class="w-full rounded-xl border-gray-200 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 p-3 text-sm resize-none bg-gray-50" placeholder="Contoh: Pembayaran diterima, kunci diserahkan...">{{ $order->admin_note }}</textarea>
                        </div>

                        <button type="button" @click="showConfirmModal = true" class="w-full py-4 bg-[#222831] text-[#DFD0B8] font-bold rounded-xl hover:bg-black hover:scale-[1.02] transition-all shadow-lg flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Keputusan
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div x-show="showConfirmModal" style="display: none;" class="fixed inset-0 z-[999] overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
             x-show="showConfirmModal"
             x-transition.opacity
             @click="showConfirmModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div x-show="showConfirmModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10 transition-colors duration-300"
                             :class="{
                                'bg-green-100 text-green-600': actionStatus === 'approved',
                                'bg-red-100 text-red-600': actionStatus === 'rejected',
                                'bg-yellow-100 text-yellow-600': actionStatus === 'pending'
                             }">
                            <i class="fa-solid text-lg"
                               :class="{
                                   'fa-check': actionStatus === 'approved',
                                   'fa-xmark': actionStatus === 'rejected',
                                   'fa-question': actionStatus === 'pending'
                               }"></i>
                        </div>

                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-bold leading-6 text-gray-900" x-text="actionStatus === 'approved' ? 'Terima Pesanan?' : (actionStatus === 'rejected' ? 'Tolak Pesanan?' : 'Simpan Perubahan?')"></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    <span x-show="actionStatus === 'approved'">
                                        Anda akan menyetujui pesanan ini. Status kamar akan berubah menjadi <strong>Terisi</strong> dan pemasukan akan dicatat ke sistem keuangan.
                                    </span>
                                    <span x-show="actionStatus === 'rejected'">
                                        Anda akan menolak pesanan ini. Status kamar akan tetap <strong>Tersedia</strong> untuk dipesan orang lain.
                                    </span>
                                    <span x-show="actionStatus === 'pending'">
                                        Status pesanan akan diubah menjadi Pending. Tidak ada perubahan pada data kamar.
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <button type="button" @click="submitForm()"
                            class="inline-flex w-full justify-center rounded-xl px-5 py-2.5 text-sm font-bold text-white shadow-sm sm:ml-3 sm:w-auto transition-colors"
                            :class="{
                                'bg-green-600 hover:bg-green-500': actionStatus === 'approved',
                                'bg-red-600 hover:bg-red-500': actionStatus === 'rejected',
                                'bg-yellow-600 hover:bg-yellow-500': actionStatus === 'pending'
                            }">
                        <span x-text="actionStatus === 'approved' ? 'Ya, Terima' : (actionStatus === 'rejected' ? 'Ya, Tolak' : 'Simpan')"></span>
                    </button>
                    <button type="button" @click="showConfirmModal = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
