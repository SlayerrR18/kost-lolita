@extends('layouts.order')

@section('title', 'Pesan Kamar - Kost Lolita')

{{-- Jika ingin header di atas konten --}}
@section('header')
    <h1 class="text-2xl font-bold text-gray-800">
        Form Pemesanan Kamar
    </h1>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-6">

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-100 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6">
        <p class="text-gray-600">
            Silakan isi data berikut untuk melanjutkan pemesanan kamar.
        </p>
    </div>

    {{-- Informasi Kamar --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Kamar</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Nomor Kamar</p>
                <p class="font-semibold text-gray-800">{{ $room->room_number }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Harga Per Bulan</p>
                <p class="font-semibold text-gray-800">
                    Rp {{ number_format($room->price, 0, ',', '.') }}
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Fasilitas</p>
                <div class="flex flex-wrap gap-2 mt-1">
                    @foreach($room->facilities ?? [] as $f)
                        <span class="px-3 py-1 bg-gray-100 rounded-lg text-xs text-gray-700">
                            {{ $f }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Form Order --}}
    <form action="{{ route('user.orders.store', $room) }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        @csrf

        <h2 class="text-xl font-semibold text-gray-800 mb-6">Data Pemesan</h2>

        {{-- Email --}}
        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-1">Email</label>
            <input type="email" value="{{ $user->email }}" disabled
                   class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2.5">
        </div>

        {{-- Nama Lengkap --}}
        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-1">Nama Lengkap</label>
            <input type="text" name="full_name"
                   value="{{ old('full_name', $user->name) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5"
                   required>
            @error('full_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nomor HP --}}
        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-1">Nomor HP</label>
            <input type="text" name="phone"
                   value="{{ old('phone') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5"
                   required>
            @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alamat --}}
        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-1">Alamat</label>
            <textarea name="address" rows="3"
                      class="w-full border border-gray-300 rounded-lg px-4 py-2.5"
                      required>{{ old('address') }}</textarea>
            @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nomor KTP --}}
        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-1">Nomor KTP</label>
            <input type="text" name="id_number"
                   value="{{ old('id_number') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5"
                   required>
            @error('id_number')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Foto KTP --}}
        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-1">Foto KTP</label>
            <input type="file" name="id_photo"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5"
                   accept="image/*"
                   required>
            @error('id_photo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <h2 class="text-xl font-semibold text-gray-800 mt-8 mb-6">Detail Sewa</h2>

        {{-- Lama Sewa --}}
        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-1">Lama Sewa (bulan)</label>
            <input type="number" name="rent_duration"
                   value="{{ old('rent_duration', 12) }}"
                   min="1"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5"
                   required>
            @error('rent_duration')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tanggal Masuk --}}
        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-1">Tanggal Masuk</label>
            <input type="date" name="start_date"
                   value="{{ old('start_date') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5"
                   required>
            @error('start_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Bukti Transfer --}}
        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-1">Foto Bukti Transfer</label>
            <input type="file" name="transfer_proof"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5"
                   accept="image/*"
                   required>
            @error('transfer_proof')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">
                Transfer ke: Bank BCA – 123456789 a.n. Kost Lolita
            </p>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('landing') }}"
               class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">
                Batal
            </a>

            <button type="submit"
                    class="px-8 py-3 bg-primary text-white rounded-lg font-semibold shadow hover:bg-accent transition">
                Kirim Pesanan
            </button>
        </div>

    </form>
</div>
@endsection
