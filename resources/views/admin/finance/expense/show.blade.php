@extends('layouts.admin-layout')

@section('title', 'Detail Pengeluaran')

@section('content')
@php use Illuminate\Support\Str; @endphp

<div x-data="{ deleteModalOpen: false, lightboxOpen: false, lightboxSrc: '' }" class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.finance.expense.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keuangan</span>
                    <h1 class="text-2xl font-serif font-bold text-[#222831]">Rincian Pengeluaran</h1>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.finance.expense.edit', $expense) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-50 transition">
                    <i class="fa-solid fa-pen-to-square"></i> <span class="hidden sm:inline ml-1">Edit</span>
                </a>
                <button @click="deleteModalOpen = true" class="px-4 py-2 bg-red-50 border border-red-100 text-red-600 rounded-xl text-sm font-bold shadow-sm hover:bg-red-100 transition">
                    <i class="fa-solid fa-trash-can"></i> <span class="hidden sm:inline ml-1">Hapus</span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden relative">
            <div class="h-2 bg-gradient-to-r from-red-500 to-red-700"></div>

            <div class="p-8">

                <div class="text-center mb-8 pb-8 border-b border-dashed border-gray-200">
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Biaya Keluar</p>
                    <h2 class="text-4xl md:text-5xl font-bold font-mono text-red-600 tracking-tight">
                        <span class="text-2xl text-red-300 align-top mr-1">Rp</span>{{ number_format($expense->amount, 0, ',', '.') }}
                    </h2>
                    <div class="mt-4 inline-flex items-center gap-2 px-3 py-1 bg-red-50 text-red-700 text-xs font-bold uppercase tracking-wide rounded-full border border-red-100">
                        <i class="fa-solid fa-arrow-trend-down"></i> Pengeluaran
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 mb-8">

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mb-1">Nama Pengeluaran</p>
                        <p class="text-lg font-bold text-gray-800">{{ $expense->name }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mb-1">Tanggal Bayar</p>
                        <p class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-regular fa-calendar text-gray-400"></i>
                            {{ $expense->date->format('d F Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mb-1">Kategori</p>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 text-xs">
                                <i class="fa-solid fa-tag"></i>
                            </div>
                            <span class="font-medium text-gray-700">
                                {{ $categories[$expense->category] ?? ucfirst($expense->category) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mb-1">Metode Pembayaran</p>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 text-xs">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                            <span class="font-medium text-gray-700 capitalize">
                                {{ $paymentMethods[$expense->payment_method] ?? $expense->payment_method }}
                            </span>
                        </div>
                    </div>

                </div>

                @if($expense->description)
                    <div class="bg-gray-50 rounded-2xl p-5 mb-8 border border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-2">Keperluan / Keterangan</p>
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $expense->description }}</p>
                    </div>
                @endif

                @if($expense->bukti_transfer)
                    <div class="border-t border-gray-100 pt-6">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-receipt"></i> Bukti Struk / Nota
                        </p>

                        @if(Str::endsWith($expense->bukti_transfer, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                            <div class="relative group w-full md:w-64 h-40 rounded-xl overflow-hidden border border-gray-200 cursor-pointer shadow-sm"
                                 @click="lightboxSrc = '{{ asset('storage/' . $expense->bukti_transfer) }}'; lightboxOpen = true">
                                <img src="{{ asset('storage/' . $expense->bukti_transfer) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                                    <i class="fa-solid fa-magnifying-glass-plus text-2xl mb-1"></i>
                                    <span class="text-[10px] font-bold uppercase">Zoom Nota</span>
                                </div>
                            </div>
                        @else
                            <a href="{{ asset('storage/' . $expense->bukti_transfer) }}" target="_blank" class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gray-100 transition group">
                                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-red-500 group-hover:scale-110 transition">
                                    <i class="fa-solid fa-file-pdf text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Dokumen Nota</p>
                                    <p class="text-xs text-gray-500">Klik untuk mengunduh</p>
                                </div>
                                <i class="fa-solid fa-download ml-auto text-gray-400"></i>
                            </a>
                        @endif
                    </div>
                @endif

            </div>

            <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-400">
                <span>Dibuat: {{ $expense->created_at->format('d M Y, H:i') }}</span>
                <span class="font-mono">ID: #EXP-{{ str_pad($expense->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

    </div>

    <div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-[99] overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" x-show="deleteModalOpen" x-transition.opacity></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="deleteModalOpen" @click.away="deleteModalOpen = false" x-transition class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl p-6 border border-gray-100 text-center">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-[#222831]">Hapus Pengeluaran?</h3>
                <p class="text-sm text-gray-500 mt-2 mb-6">
                    Data <strong>{{ $expense->name }}</strong> senilai <strong>Rp {{ number_format($expense->amount, 0, ',', '.') }}</strong> akan dihapus permanen dari laporan.
                </p>
                <div class="flex gap-2 justify-center">
                    <button @click="deleteModalOpen = false" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-50">Batal</button>
                    <form action="{{ route('admin.finance.expense.destroy', $expense) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-red-600 text-white font-bold text-sm hover:bg-red-700 shadow-md">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-hidden" x-cloak>
        <div class="absolute inset-0 bg-black/95 backdrop-blur-sm transition-opacity" @click="lightboxOpen = false"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div class="relative max-w-4xl w-full pointer-events-auto" x-show="lightboxOpen" x-transition.scale>
                <button @click="lightboxOpen = false" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <img :src="lightboxSrc" class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl">
            </div>
        </div>
    </div>

</div>
@endsection
