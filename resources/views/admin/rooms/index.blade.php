@extends('layouts.admin-layout')

@section('content')
<div x-data="{
    deleteModalOpen: false,
    deleteAction: '',
    roomName: ''
}" class="py-6">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h2 class="text-3xl font-serif font-bold text-[#222831]">Daftar Kamar</h2>
                <p class="text-gray-500 mt-1 text-sm">Kelola ketersediaan dan informasi kamar kost.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.rooms.create') }}"
                   class="inline-flex items-center gap-2 bg-[#222831] text-[#DFD0B8] px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Kamar</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#222831] text-[#DFD0B8]">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold uppercase tracking-wider rounded-tl-3xl">Nomor Kamar</th>
                            <th class="px-6 py-4 text-sm font-semibold uppercase tracking-wider">Tipe / Fasilitas</th>
                            <th class="px-6 py-4 text-sm font-semibold uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-4 text-sm font-semibold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold uppercase tracking-wider rounded-tr-3xl text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach($rooms as $room)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 group">

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-[#222831] font-bold group-hover:bg-[#DFD0B8] transition-colors">
                                        {{ $room->room_number }}
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="font-medium text-[#222831] block">Standard Room</span> <span class="text-xs text-gray-400">AC, Wifi, Kamar Mandi Dalam</span> </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-[#222831]">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-400">/bln</span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(strtolower($room->status) == 'available')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        Tersedia
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                        Terisi
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('admin.rooms.show', $room) }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-blue-500 hover:bg-blue-50 transition-colors" title="Lihat Detail">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.rooms.edit', $room) }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-yellow-600 hover:bg-yellow-50 transition-colors" title="Edit Data">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>

                                    <button
                                        @click="deleteModalOpen = true; deleteAction = '{{ route('admin.rooms.destroy', $room) }}'; roomName = '{{ $room->room_number }}'"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors"
                                        title="Hapus Kamar">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(method_exists($rooms, 'links'))
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $rooms->links() }}
                </div>
            @endif
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
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-serif font-bold text-[#222831]">Hapus Kamar?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Anda yakin ingin menghapus data <strong>Kamar <span x-text="roomName"></span></strong>? <br>
                                    Data yang dihapus tidak dapat dikembalikan.
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
