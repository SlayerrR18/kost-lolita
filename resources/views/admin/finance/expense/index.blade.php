@extends('layouts.admin-layout')

@section('title', 'Laporan Pengeluaran - Admin Kost Lolita')

@section('content')
<div x-data="{
    deleteModalOpen: false,
    deleteAction: '',
    expenseName: ''
}" class="py-8">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-serif font-bold text-[#222831]">Laporan Pengeluaran</h1>
                <p class="text-gray-500 text-sm mt-1">
                    Kelola dan pantau semua pengeluaran operasional kost.
                </p>
            </div>

            <a href="{{ route('admin.finance.expense.create') }}"
               class="inline-flex items-center gap-2 bg-[#222831] text-[#DFD0B8] px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <i class="fa-solid fa-plus"></i>
                <span>Catat Pengeluaran</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pengeluaran</p>
                    <h3 class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-money-bill-wave text-xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah Transaksi</p>
                    <h3 class="text-2xl font-bold text-[#222831] mt-1">{{ $expenses->total() }} <span class="text-sm font-normal text-gray-400">Item</span></h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-receipt text-xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rata-rata / Item</p>
                    <h3 class="text-2xl font-bold text-[#222831] mt-1">
                        Rp {{ $expenses->total() > 0 ? number_format($totalExpense / $expenses->total(), 0, ',', '.') : '0' }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-500 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-chart-pie text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8" x-data="{ showFilter: false }">
            <div class="flex justify-between items-center cursor-pointer" @click="showFilter = !showFilter">
                <h3 class="font-bold text-[#222831] flex items-center gap-2">
                    <i class="fa-solid fa-filter text-[#DFD0B8]"></i> Filter Data
                </h3>
                <i class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-300" :class="showFilter ? 'rotate-180' : ''"></i>
            </div>

            <div x-show="showFilter" x-collapse style="display: none;">
                <form method="GET" action="{{ route('admin.finance.expense.index') }}" class="mt-6 pt-6 border-t border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Kategori</label>
                            <select name="category" class="w-full rounded-xl border-gray-200 text-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ ($filters['category'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 text-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 text-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 bg-[#222831] text-[#DFD0B8] py-2.5 rounded-xl text-sm font-bold hover:shadow-lg transition-all">
                                Terapkan
                            </button>
                            <a href="{{ route('admin.finance.expense.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition-colors" title="Reset Filter">
                                <i class="fa-solid fa-rotate-right"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 flex items-center gap-3 text-green-800">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#222831] text-[#DFD0B8]">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider rounded-tl-3xl">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right">Jumlah</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center rounded-tr-3xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-600">{{ $expense->date->format('d M Y') }}</span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-[#222831]">{{ $expense->name }}</div>
                                    @if($expense->description)
                                        <div class="text-xs text-gray-400 truncate max-w-[200px]">{{ $expense->description }}</div>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $catColor = match($expense->category) {
                                            'listrik' => 'bg-yellow-100 text-yellow-700',
                                            'air' => 'bg-blue-100 text-blue-700',
                                            'wifi' => 'bg-purple-100 text-purple-700',
                                            'maintenance' => 'bg-orange-100 text-orange-700',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $catColor }}">
                                        {{ $categories[$expense->category] ?? ucfirst($expense->category) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-500 capitalize">
                                    {{ $expense->payment_method }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-bold text-red-600">
                                        - Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">

                                        <a href="{{ route('admin.finance.expense.show', $expense) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-blue-600 hover:bg-blue-50 transition-colors"
                                        title="Lihat Detail">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.finance.expense.edit', $expense) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-yellow-600 hover:bg-yellow-50 transition-colors"
                                        title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <button @click="deleteModalOpen = true; deleteAction = '{{ route('admin.finance.expense.destroy', $expense) }}'; expenseName = '{{ $expense->name }}'"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors"
                                                title="Hapus">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fa-solid fa-file-invoice-dollar text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada data pengeluaran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($expenses->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>

    </div>

    <div x-show="deleteModalOpen"
         style="display: none;"
         class="fixed inset-0 z-[999] overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>

        <div x-show="deleteModalOpen"
             x-transition.opacity
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
                            <h3 class="text-lg font-serif font-bold text-[#222831]">Hapus Data Pengeluaran?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Anda yakin ingin menghapus data <strong><span x-text="expenseName"></span></strong>? <br>
                                    Data keuangan yang dihapus akan mempengaruhi laporan akhir.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <form :action="deleteAction" method="POST" class="w-full sm:w-auto">
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
