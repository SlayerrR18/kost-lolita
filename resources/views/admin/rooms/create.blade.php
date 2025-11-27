@extends('layouts.admin-layout')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Tambah Kamar</h2>

            <form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Nomor Kamar -->
                <div>
                    <label for="room_number" class="block text-sm font-medium text-gray-700">Nomor Kamar</label>
                    <input
                        type="text"
                        name="room_number"
                        id="room_number"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        required
                    >
                </div>

                <!-- Harga -->
                <div class="mt-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                    <input
                        type="number"
                        name="price"
                        id="price"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        required
                    >
                </div>

                <!-- Fasilitas -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Fasilitas</label>
                    <p class="text-xs text-gray-500 mt-1">
                        Pilih fasilitas default yang tersedia di kamar ini, lalu tambahkan fasilitas lain jika ada.
                    </p>

                    @php
                        $defaultFacilities = [
                            'Tempat tidur',
                            'Kamar mandi dalam',
                            'Meja & kursi',
                            'Lemari',
                        ];
                    @endphp

                    <div class="mt-3 space-y-2">
                        @foreach($defaultFacilities as $facility)
                            <label class="inline-flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="facilities[]"
                                    value="{{ $facility }}"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm"
                                >
                                <span class="text-sm text-gray-700">{{ $facility }}</span>
                            </label><br>
                        @endforeach
                    </div>

                    <!-- Fasilitas tambahan -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Fasilitas lain (opsional)
                        </label>
                        <p class="text-xs text-gray-500 mb-2">
                            Isi fasilitas tambahan di sini, contoh: "Dispenser", "Wi-Fi", "Parkiran motor", dll.
                        </p>

                        <div id="extraFacilitiesWrapper" class="space-y-2">
                            <div class="flex gap-2">
                                <input
                                    type="text"
                                    name="additional_facilities[]"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="Contoh: Wi-Fi"
                                >
                            </div>
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

                <!-- Foto -->
                <div class="mt-4">
                    <label for="photos" class="block text-sm font-medium text-gray-700">Foto Kamar</label>
                    <input
                        type="file"
                        name="photos[]"
                        multiple
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    >
                    <p class="text-xs text-gray-500 mt-1">
                        Bisa upload lebih dari satu foto (JPEG, PNG, max 2MB per file).
                    </p>
                </div>

                <!-- Status -->
                <div class="mt-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select
                        name="status"
                        id="status"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        required
                    >
                        <option value="available">Tersedia</option>
                        <option value="occupied">Terisi</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button
                        type="submit"
                        class="px-6 py-3 text-lg font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                    >
                        Simpan Kamar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JS kecil untuk tambah field fasilitas lain --}}
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
