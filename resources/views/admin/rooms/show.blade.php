@extends('layouts.admin-layout')

@section('content')
@php
    // Ambil foto pertama sebagai default, atau placeholder jika kosong
    $firstPhoto = $room->photos && count($room->photos) > 0 ? Storage::url($room->photos[0]) : null;
@endphp

<div class="py-8" x-data="{
    activePhoto: '{{ $firstPhoto }}',
    deleteModalOpen: false
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.rooms.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Detail Unit</span>
                    <h2 class="text-3xl font-serif font-bold text-[#222831]">Kamar {{ $room->room_number }}</h2>
                </div>
            </div>

            <div class="hidden sm:block">
                @if($room->status == 'available')
                    <span class="px-6 py-2 rounded-full bg-green-100 text-green-700 font-bold border border-green-200 shadow-sm flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-600 animate-pulse"></span> Tersedia
                    </span>
                @else
                    <span class="px-6 py-2 rounded-full bg-gray-100 text-gray-600 font-bold border border-gray-200 shadow-sm flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-gray-500"></span> Terisi
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-4">
                <div class="w-full h-[400px] sm:h-[500px] bg-gray-100 rounded-3xl overflow-hidden shadow-lg border border-gray-100 relative group">
                    <template x-if="activePhoto">
                        <img :src="activePhoto" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Foto Kamar">
                    </template>
                    <template x-if="!activePhoto">
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                            <i class="fa-regular fa-image text-6xl opacity-30 mb-4"></i>
                            <span class="text-sm uppercase tracking-widest">Tidak ada foto</span>
                        </div>
                    </template>
                </div>

                @if($room->photos)
                <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                    @foreach($room->photos as $photo)
                        <button @click="activePhoto = '{{ Storage::url($photo) }}'"
                                class="flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden border-2 transition-all duration-200 hover:opacity-100 focus:outline-none"
                                :class="activePhoto === '{{ Storage::url($photo) }}' ? 'border-[#222831] opacity-100 ring-2 ring-[#222831] ring-offset-2' : 'border-transparent opacity-60 hover:border-gray-300'">
                            <img src="{{ Storage::url($photo) }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="space-y-6">

                <div class="bg-white p-6 rounded-3xl shadow-md border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Harga Sewa</p>
                    <div class="flex items-end gap-1">
                        <h3 class="text-4xl font-bold text-[#222831]">Rp {{ number_format($room->price, 0, ',', '.') }}</h3>
                        <span class="text-gray-400 font-medium mb-1">/ bulan</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-md border border-gray-100">
                    <h4 class="text-lg font-bold text-[#222831] mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-star text-[#DFD0B8]"></i> Fasilitas
                    </h4>

                    <div class="flex flex-wrap gap-2">
                        @forelse($room->facilities as $facility)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-50 border border-gray-200 text-sm font-medium text-gray-700 hover:bg-[#222831] hover:text-[#DFD0B8] hover:border-[#222831] transition-colors cursor-default">
                                <i class="fa-solid fa-check mr-2 text-xs opacity-50"></i> {{ $facility }}
                            </span>
                        @empty
                            <span class="text-gray-400 italic text-sm">Belum ada data fasilitas.</span>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-md border border-gray-100">
                    <h4 class="text-lg font-bold text-[#222831] mb-2">Informasi Tambahan</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Kamar ini berada di lokasi strategis dalam area kost. Cocok untuk mahasiswa atau pekerja yang membutuhkan ketenangan.
                        </p>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a href="{{ route('admin.rooms.edit', $room) }}" class="flex items-center justify-center gap-2 bg-[#DFD0B8] text-[#222831] py-3.5 rounded-xl font-bold hover:bg-[#d4c2a3] transition-colors shadow-sm">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>

                    <button @click="deleteModalOpen = true" class="flex items-center justify-center gap-2 bg-white border border-red-200 text-red-500 py-3.5 rounded-xl font-bold hover:bg-red-50 hover:border-red-300 transition-colors shadow-sm">
                        <i class="fa-regular fa-trash-can"></i> Hapus
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div x-show="deleteModalOpen"
         style="display: none;"
         class="fixed inset-0 z-[99] overflow-y-auto"
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
                            <h3 class="text-lg font-serif font-bold text-[#222831]">Hapus Kamar {{ $room->room_number }}?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Tindakan ini tidak dapat dibatalkan. Semua data terkait kamar ini (termasuk riwayat sewa) mungkin akan terhapus.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors">
                            Ya, Hapus Permanen
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
