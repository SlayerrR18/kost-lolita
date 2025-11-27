@extends('layouts.admin-layout')

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.rooms.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-serif font-bold text-[#222831]">Tambah Kamar Baru</h2>
                <p class="text-sm text-gray-500">Lengkapi informasi di bawah untuk menambahkan unit kamar.</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

            <form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data" class="p-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-[#222831] border-b border-gray-100 pb-2">Informasi Dasar</h3>

                        <div>
                            <label for="room_number" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Kamar</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-door-closed"></i>
                                </span>
                                <input type="text" name="room_number" id="room_number"
                                    class="pl-10 block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all placeholder-gray-300"
                                    placeholder="Contoh: A-101" required value="{{ old('room_number') }}">
                            </div>
                            @error('room_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-700 mb-1">Harga Sewa (Per Bulan)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 font-bold">Rp</span>
                                <input type="number" name="price" id="price"
                                    class="pl-10 block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all placeholder-gray-300"
                                    placeholder="0" required value="{{ old('price') }}">
                            </div>
                            @error('price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status Ketersediaan</label>
                            <select name="status" id="status" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all">
                                <option value="available">Tersedia (Siap Huni)</option>
                                <option value="occupied">Terisi (Sedang Disewa)</option>
                                <option value="maintenance">Maintenance (Perbaikan)</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-[#222831] border-b border-gray-100 pb-2">Detail & Foto</h3>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Fasilitas Tersedia</label>
                            <div class="flex flex-wrap gap-3">
                                @php
                                    $facilities = ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Kasur', 'Lemari', 'Meja', 'TV', 'Air Panas'];
                                @endphp
                                @foreach($facilities as $facility)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="facilities[]" value="{{ $facility }}" class="peer sr-only">
                                        <div class="px-4 py-2 rounded-lg border border-gray-200 bg-white text-gray-500 text-sm font-medium transition-all peer-checked:bg-[#222831] peer-checked:text-[#DFD0B8] peer-checked:border-[#222831] hover:bg-gray-50">
                                            {{ $facility }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="mt-4" x-data="{ extras: [] }">
                                <template x-for="(extra, index) in extras" :key="index">
                                    <div class="flex gap-2 mt-2">
                                        <input type="text" name="additional_facilities[]" class="block w-full rounded-lg border-gray-200 text-sm shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20" placeholder="Fasilitas Lainnya...">
                                        <button type="button" @click="extras.splice(index, 1)" class="text-red-500 hover:text-red-700 px-2"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                </template>
                                <button type="button" @click="extras.push('')" class="mt-2 text-sm text-[#222831] font-medium hover:underline flex items-center gap-1">
                                    <i class="fa-solid fa-plus-circle"></i> Tambah Lainnya
                                </button>
                            </div>
                        </div>

                        <div x-data="imagePreview()">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Kamar</label>

                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 transition-colors cursor-pointer relative" @click="$refs.fileInput.click()">
                                <input type="file" name="photos[]" multiple class="hidden" x-ref="fileInput" @change="previewImages">

                                <div x-show="images.length === 0" class="space-y-2">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto text-gray-400">
                                        <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-500">Klik untuk upload atau drag & drop</p>
                                    <p class="text-xs text-gray-400">PNG, JPG up to 2MB</p>
                                </div>

                                <div x-show="images.length > 0" class="grid grid-cols-3 gap-2">
                                    <template x-for="img in images" :key="img">
                                        <div class="relative aspect-square rounded-lg overflow-hidden border border-gray-200">
                                            <img :src="img" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.rooms.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#222831] text-[#DFD0B8] font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                        <i class="fa-solid fa-save"></i>
                        Simpan Data
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function imagePreview() {
        return {
            images: [],
            previewImages(e) {
                this.images = [];
                const files = e.target.files;
                if (files) {
                    for (let i = 0; i < files.length; i++) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.images.push(e.target.result);
                        }
                        reader.readAsDataURL(files[i]);
                    }
                }
            }
        }
    }
</script>
@endsection
