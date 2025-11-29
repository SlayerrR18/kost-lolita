@extends('layouts.admin-layout')

@section('title', 'Detail Pendapatan - Admin Kost Lolita')

@section('content')
<div x-data="{ deleteModalOpen: false }" class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.finance.income.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keuangan</span>
                <h1 class="text-2xl font-serif font-bold text-[#222831]">Rincian Pemasukan</h1>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden relative">

            <div class="bg-[#222831] p-8 text-white flex flex-col md:flex-row justify-between items-start md:items-center relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                    <i class="fa-solid fa-hand-holding-dollar text-9xl text-white"></i>
                </div>

                <div class="relative z-10">
                    <p class="text-green-400 text-sm font-bold uppercase tracking-widest mb-1">Total Terima</p>
                    <h2 class="text-4xl font-bold font-mono text-white">
                        + Rp {{ number_format($income->amount, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="mt-4 md:mt-0 relative z-10">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20">
                        <i class="fa-regular fa-calendar text-green-400"></i>
                        <span class="font-medium">{{ $income->date->format('d F Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="p-8">

                <div class="mb-8 border-b border-dashed border-gray-200 pb-6">
                    <h3 class="text-xl font-bold text-[#222831] mb-2">{{ $income->source }}</h3>
                    @if($income->description)
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $income->description }}</p>
                    @else
                        <p class="text-gray-400 text-sm italic">Tidak ada catatan tambahan.</p>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mb-2">Kategori Pendapatan</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">
                                    {{ $categories[$income->category] ?? ucfirst($income->category) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mb-2">Metode Pembayaran</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 capitalize">
                                    {{ $paymentMethods[$income->payment_method] ?? $income->payment_method }}
                                </p>
                                @if($income->reference)
                                    <p class="text-xs text-gray-500">Ref: {{ $income->reference }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex justify-between items-center text-xs text-gray-400 pt-6 border-t border-gray-100">
                    <span>Dibuat: {{ $income->created_at->format('d M Y, H:i') }}</span>
                    <span>Terakhir Update: {{ $income->updated_at->format('d M Y, H:i') }}</span>
                </div>

            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('admin.finance.income.edit', $income) }}"
               class="px-6 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 font-bold hover:bg-gray-50 hover:text-yellow-600 hover:border-yellow-200 transition-all shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i> Edit Data
            </a>

            <button @click="deleteModalOpen = true"
                    class="px-6 py-3 rounded-xl bg-red-50 text-red-600 border border-red-100 font-bold hover:bg-red-100 transition-all shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-trash-can"></i> Hapus
            </button>
        </div>

    </div>

    <div x-show="deleteModalOpen"
         style="display: none;"
         class="fixed inset-0 z-[999] overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>

        <div x-show="deleteModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div x-show="deleteModalOpen"
                 @click.away="deleteModalOpen = false"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-serif font-bold text-[#222831]">Hapus Data Pendapatan?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Anda yakin ingin menghapus data dari <strong>{{ $income->source }}</strong> senilai <strong>Rp {{ number_format($income->amount, 0, ',', '.') }}</strong>? <br>
                                    Data yang dihapus akan mempengaruhi laporan keuangan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <form action="{{ route('admin.finance.income.destroy', $income) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors">
                            Ya, Hapus
                        </button>
                    </form>
                    <button @click="deleteModalOpen = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
