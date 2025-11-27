@extends('layouts.admin-layout')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Edit Kamar: {{ $room->room_number }}</h2>

            @php
                // Fasilitas default
                $defaultFacilities = [
                    'Tempat tidur',
                    'Kamar mandi dalam',
                    'Meja & kursi',
                    'Lemari',
                ];

                $facilities = $room->facilities ?? [];

                // Fasilitas tambahan = yang bukan default
                $extraFacilities = array_values(array_diff($facilities, $defaultFacilities));
            @endphp

            <form method="POST" action="{{ route('admin.rooms.update', $room) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nomor Kamar --}}
                <div>
                    <label for="room_number" class="block text-sm font-medium text-gray-700">Nomor Kamar</label>
                    <input
                        type="text"
                        name="room_number"
                        id="room_number"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        value="{{ old('room_number', $room->room_number) }}"
                        required
                    >
                </div>

                {{-- Harga --}}
                <div class="mt-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                    <input
                        type="number"
                        name="price"
                        id="price"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        value="{{ old('price', $room->price) }}"
                        required
                    >
                </div>

                {{-- Fasilitas --}}
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Fasilitas</label>
                    <p class="text-xs text-gray-500 mt-1">
                        Centang fasilitas default yang tersedia di kamar ini, lalu tambahkan fasilitas lain jika ada.
                    </p>

                    {{-- Checkbox fasilitas default --}}
                    <div class="mt-3 space-y-2">
                        @foreach($defaultFacilities as $facility)
                            <label class="inline-flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="facilities[]"
                                    value="{{ $facility }}"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm"
                                    {{ in_array($facility, $facilities) ? 'checked' : '' }}
                                >
                                <span class="text-sm text-gray-700">{{ $facility }}</span>
                            </label><br>
                        @endforeach
                    </div>

                    {{-- Fasilitas tambahan --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Fasilitas lain (opsional)
                        </label>
                        <p class="text-xs text-gray-500 mb-2">
                            Isi fasilitas tambahan di sini, contoh: "Wi-Fi", "Parkiran motor", dll.
                        </p>

                        <div id="extraFacilitiesWrapper" class="space-y-2">
                            @if(count($extraFacilities))
                                @foreach($extraFacilities as $extra)
                                    <div class="flex gap-2">
                                        <input
                                            type="text"
                                            name="additional_facilities[]"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                            value="{{ $extra }}"
                                            placeholder="Fasilitas lain..."
                                        >
                                    </div>
                                @endforeach
                            @else
                                {{-- Jika belum ada fasilitas tambahan, sediakan satu input kosong --}}
                                <div class="flex gap-2">
                                    <input
                                        type="text"
                                        name="additional_facilities[]"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        placeholder="Contoh: Wi-Fi"
                                    >
                                </div>
                            @endif
                        </div>

                        <button
                            type="button"
                            id="addFacilityButton"
                            class="mt-3 inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50"
                        >
                            + Tambah fasilitas lain
                        </button>
                    </div>
                </div>

                {{-- Foto --}}
                <div class="mt-4">
                    <label for="photos" class="block text-sm font-medium text-gray-700">Foto Kamar</label>
                    <input
                        type="file"
                        name="photos[]"
                        multiple
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    >
                    <p class="text-xs text-gray-500 mt-1">
                        Bisa upload lebih dari satu foto (JPEG, PNG, max 2MB per file). Foto lama tetap tersimpan, yang baru akan ditambahkan.
                    </p>
                </div>

                {{-- Status --}}
                <div class="mt-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select
                        name="status"
                        id="status"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        required
                    >
                        <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Terisi</option>
                    </select>
                </div>

                {{-- Tombol Submit --}}
                <div class="mt-6">
                    <button
                        type="submit"
                        class="px-6 py-3 text-lg font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addButton = document.getElementById('addFacilityButton');
        const wrapper   = document.getElementById('extraFacilitiesWrapper');

        if (addButton && wrapper) {
            addButton.addEventListener('click', function () {
                const div = document.createElement('div');
                div.className = 'flex gap-2';

                div.innerHTML = `
                    <input
                        type="text"
                        name="additional_facilities[]"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        placeholder="Fasilitas lain..."
                    >
                `;

                wrapper.appendChild(div);
            });
        }
    });
</script>
@endsection
