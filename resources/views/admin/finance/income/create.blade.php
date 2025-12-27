@extends('layouts.admin-layout')

@section('title', 'Tambah Pendapatan')

@section('content')
<div class="py-8" x-data="incomeForm()">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('admin.finance.income.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-[#222831] mb-3 transition group">
                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-[#222831] transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </div>
                Kembali ke Keuangan
            </a>
            <h1 class="text-3xl font-serif font-bold text-[#222831]">Catat Pendapatan</h1>
            <p class="text-gray-500 mt-1">Input data pemasukan baru untuk pembukuan kost.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden relative">
            <div class="h-1.5 bg-gradient-to-r from-[#222831] to-[#393E46]"></div>

            <form action="{{ route('admin.finance.income.store') }}" method="POST" enctype="multipart/form-data" class="p-8" @submit="prepareSubmit">
                @csrf

                <div class="mb-8 bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <label for="amount_display" class="block text-xs font-bold text-gray-500 uppercase mb-2">Total Pendapatan (Nominal)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#222831] font-bold text-xl">Rp</span>

                        <input type="text" id="amount_display" x-model="formattedAmount" @input="formatCurrency"
                               class="pl-12 block w-full bg-white rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500/20 transition-all font-mono font-bold text-3xl text-gray-800 placeholder-gray-300 py-3"
                               placeholder="0" autofocus>

                        <input type="hidden" name="amount" x-model="realAmount">
                    </div>
                    @error('amount') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Sumber Pendapatan <span class="text-red-500">*</span></label>
                        <input type="text" name="source" value="{{ old('source') }}" placeholder="Contoh: Sewa Kamar 101 - Budi"
                               class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition py-3 px-4">
                        @error('source') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori</label>
                        <div class="relative">
                            <select name="category" class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition py-3 px-4 appearance-none cursor-pointer">
                                <option value="" disabled selected>Pilih Kategori</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Terima</label>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}"
                               class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition py-3 px-4 cursor-pointer">
                        @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Metode Pembayaran</label>
                        <div class="relative">
                            <select name="payment_method" class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition py-3 px-4 appearance-none cursor-pointer">
                                <option value="" disabled selected>Pilih Metode</option>
                                @foreach($paymentMethods as $key => $label)
                                    <option value="{{ $key }}" {{ old('payment_method') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <i class="fa-solid fa-credit-card text-gray-400"></i>
                            </div>
                        </div>
                        @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="border-t border-dashed border-gray-200 pt-6 mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Bukti Transfer</label>

                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-1 transition-colors hover:border-[#222831] hover:bg-gray-50"
                                 :class="{'border-[#222831] bg-gray-50': isDragging}"
                                 @dragover.prevent="isDragging = true"
                                 @dragleave.prevent="isDragging = false"
                                 @drop.prevent="isDragging = false; handleDrop($event)">

                                <div class="relative cursor-pointer h-32 flex flex-col items-center justify-center rounded-lg overflow-hidden"
                                     @click="$refs.fileInput.click()">

                                    <input type="file" name="bukti_transfer" x-ref="fileInput" class="hidden" accept="image/*,application/pdf" @change="previewFile">

                                    <div x-show="!filePreview" class="text-center p-4">
                                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-300 mb-2"></i>
                                        <p class="text-xs font-bold text-gray-600">Klik / Tarik File</p>
                                        <p class="text-[10px] text-gray-400">JPG, PNG, PDF (Max 5MB)</p>
                                    </div>

                                    <div x-show="filePreview && fileType === 'image'" class="w-full h-full relative group">
                                        <img :src="filePreview" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 hidden group-hover:flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">Ganti File</span>
                                        </div>
                                    </div>

                                    <div x-show="filePreview && fileType !== 'image'" class="flex items-center gap-2 p-4 bg-gray-100 rounded">
                                        <i class="fa-solid fa-file-pdf text-red-500 text-xl"></i>
                                        <span class="text-xs font-bold text-gray-700" x-text="fileName"></span>
                                    </div>
                                </div>
                            </div>
                            @error('bukti_transfer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan (Opsional)</label>
                            <textarea name="description" rows="4" class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition p-3 resize-none" placeholder="Keterangan tambahan...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8">
                    <a href="{{ route('admin.finance.income.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-[#222831] text-[#DFD0B8] font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i> Simpan Pendapatan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function incomeForm() {
        return {
            formattedAmount: '',
            realAmount: '',
            filePreview: null,
            fileName: '',
            fileType: '',
            isDragging: false,

            // Format Currency: 10000 -> 10.000
            formatCurrency(e) {
                let value = e.target.value.replace(/[^0-9]/g, ''); // Hapus non-angka
                if (value === '') {
                    this.formattedAmount = '';
                    this.realAmount = '';
                    return;
                }

                this.realAmount = value; // Simpan angka murni untuk backend
                this.formattedAmount = new Intl.NumberFormat('id-ID').format(value);
            },

            // Handle File Preview
            previewFile(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.processFile(file);
            },

            handleDrop(event) {
                const file = event.dataTransfer.files[0];
                if (!file) return;

                // Assign to input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                this.$refs.fileInput.files = dataTransfer.files;

                this.processFile(file);
            },

            processFile(file) {
                this.fileName = file.name;

                if (file.type.startsWith('image/')) {
                    this.fileType = 'image';
                    const reader = new FileReader();
                    reader.onload = (e) => { this.filePreview = e.target.result; };
                    reader.readAsDataURL(file);
                } else {
                    this.fileType = 'file';
                    this.filePreview = true; // Flag as has file
                }
            },

            prepareSubmit() {
                // Logic tambahan sebelum submit jika perlu
            }
        }
    }
</script>
@endsection
