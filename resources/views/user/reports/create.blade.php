@extends('layouts.user-layout')

@section('title', 'Buat Laporan Baru')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('user.reports.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-[#222831] transition mb-3 group">
                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-[#222831] transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </div>
                Kembali ke Riwayat
            </a>
            <h1 class="text-3xl font-serif font-bold text-[#222831]">Sampaikan Laporan</h1>
            <p class="text-gray-500 mt-1">Ceritakan kendala atau masukan Anda untuk kenyamanan bersama.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden" x-data="reportForm()">

            <div class="h-2 bg-gradient-to-r from-[#222831] to-[#393E46]"></div>

            <div class="p-8">

                {{-- Error Validation Alert --}}
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                        <div>
                            <p class="font-bold">Mohon perbaiki kesalahan berikut:</p>
                            <ul class="list-disc list-inside mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('user.reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Kejadian</label>
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}"
                                   class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition py-3">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori Masalah</label>
                            <div class="relative">
                                <select name="category" class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition py-3 appearance-none">
                                    <option value="fasilitas" selected>🔧 Fasilitas Rusak</option>
                                    <option value="kebersihan">🧹 Kebersihan</option>
                                    <option value="keamanan">🛡️ Keamanan</option>
                                    <option value="lainnya">📝 Lainnya / Saran</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-data="{ count: 0, max: 1000 }">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Detail Laporan</label>
                        <textarea name="message" rows="6"
                                  placeholder="Jelaskan secara rinci lokasi kerusakan, kronologi, atau saran Anda..."
                                  x-on:input="count = $el.value.length"
                                  maxlength="1000"
                                  class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition p-4 resize-none">{{ old('message') }}</textarea>

                        <div class="flex justify-between items-center mt-2 text-xs text-gray-400">
                            <span>*Mohon gunakan bahasa yang sopan.</span>
                            <span :class="count > 900 ? 'text-red-500 font-bold' : ''">
                                <span x-text="count"></span> / <span x-text="max"></span>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Foto Bukti (Opsional)</label>

                        <div class="border-2 border-dashed border-gray-300 rounded-2xl p-1 transition-colors hover:border-[#222831] hover:bg-gray-50"
                             :class="{'border-[#222831] bg-gray-50': isDragging}"
                             @dragover.prevent="isDragging = true"
                             @dragleave.prevent="isDragging = false"
                             @drop.prevent="isDragging = false; handleDrop($event)">

                            <div class="relative cursor-pointer h-48 flex flex-col items-center justify-center rounded-xl overflow-hidden"
                                 @click="$refs.fileInput.click()">

                                <input type="file" name="photo" x-ref="fileInput" class="hidden" accept="image/*" @change="previewFile">

                                <div x-show="!photoPreview" class="text-center p-6">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                        <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700">Klik untuk upload</p>
                                    <p class="text-xs text-gray-400 mt-1">atau tarik file ke sini (Max 5MB)</p>
                                </div>

                                <template x-if="photoPreview">
                                    <div class="relative w-full h-full group">
                                        <img :src="photoPreview" class="w-full h-full object-contain bg-gray-100">

                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <button type="button" @click.stop="removeFile" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg hover:bg-red-600 transition">
                                                <i class="fa-solid fa-trash mr-2"></i> Hapus Foto
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('user.reports.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3 rounded-xl bg-[#222831] text-[#DFD0B8] font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <span>Kirim Laporan</span>
                            <i class="fa-regular fa-paper-plane"></i>
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
    function reportForm() {
        return {
            photoPreview: null,
            isDragging: false,

            previewFile(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            handleDrop(event) {
                const file = event.dataTransfer.files[0];
                if (!file) return;

                // Set file to input manually
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                this.$refs.fileInput.files = dataTransfer.files;

                // Trigger preview
                this.previewFile({ target: this.$refs.fileInput });
            },

            removeFile() {
                this.photoPreview = null;
                this.$refs.fileInput.value = null;
            }
        };
    }
</script>
@endsection
