@extends('layouts.admin-layout')

@section('title', 'Tambah Pengeluaran - Admin Kost Lolita')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.finance.expense.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keuangan</span>
                <h1 class="text-2xl font-serif font-bold text-[#222831]">Catat Pengeluaran Baru</h1>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-[#222831] px-8 py-4 flex items-center justify-between">
                <h2 class="text-[#DFD0B8] font-bold text-lg flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Form Input
                </h2>
                <span class="text-xs text-gray-400 font-mono">{{ date('d M Y') }}</span>
            </div>

            <form action="{{ route('admin.finance.expense.store') }}" method="POST" class="p-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Pengeluaran <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-solid fa-tag"></i>
                            </span>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Beli Token Listrik / Service AC"
                                   class="pl-10 block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all placeholder-gray-300" required autofocus>
                        </div>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-6">

                        <div>
                            <label for="amount" class="block text-sm font-semibold text-gray-700 mb-1">Jumlah (Nominal) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#222831] font-bold">Rp</span>
                                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0" placeholder="0"
                                       class="pl-10 block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all font-mono font-bold text-lg placeholder-gray-300" required>
                            </div>
                            @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-1">Kategori Pengeluaran <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-layer-group"></i>
                                </span>
                                <select name="category" id="category" class="pl-10 block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach($categories as $key => $label)
                                        <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    <div class="space-y-6">

                        <div>
                            <label for="date" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Transaksi <span class="text-red-500">*</span></label>
                            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}"
                                   class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all" required>
                            @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-semibold text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-credit-card"></i>
                                </span>
                                <select name="payment_method" id="payment_method" class="pl-10 block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all" required>
                                    <option value="" disabled selected>Pilih Metode</option>
                                    @foreach($paymentMethods as $key => $label)
                                        <option value="{{ $key }}" {{ old('payment_method') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    <div class="md:col-span-2 space-y-6 border-t border-dashed border-gray-200 pt-6 mt-2">

                        <div>
                            <label for="reference" class="block text-sm font-semibold text-gray-700 mb-1">No. Referensi / Invoice (Opsional)</label>
                            <input type="text" name="reference" id="reference" value="{{ old('reference') }}" placeholder="Contoh: INV-2025-001"
                                   class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all placeholder-gray-300">
                            @error('reference') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                            <textarea name="description" id="description" rows="3" placeholder="Tulis detail pengeluaran di sini..."
                                      class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all placeholder-gray-300">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>

                </div>

                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.finance.expense.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#222831] text-[#DFD0B8] font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle"></i> Simpan Data
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
