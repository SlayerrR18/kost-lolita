@extends('layouts.admin-layout')

@section('title', 'Detail Pesanan - Admin Kost Lolita')

@section('content')
<div class="px-6 py-6 max-w-5xl">

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan</h1>
            <p class="text-gray-500 text-sm mt-1">
                ID Pesanan: #{{ $order->id }}
            </p>
        </div>

        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
           class="text-sm text-gray-600 hover:text-gray-900 underline">
            &larr; Kembali ke daftar pesanan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom kiri: data pemesan + sewa --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Data Pemesan --}}
            <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Data Pemesan</h2>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Nama Lengkap</dt>
                        <dd class="font-medium text-gray-800">{{ $order->full_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Email</dt>
                        <dd class="font-medium text-gray-800">{{ $order->email }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Nomor HP</dt>
                        <dd class="font-medium text-gray-800">{{ $order->phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 mb-1">Alamat</dt>
                        <dd class="font-medium text-gray-800 text-right">
                            {{ $order->address }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Nomor KTP</dt>
                        <dd class="font-medium text-gray-800">{{ $order->id_number }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Data Sewa --}}
            <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Sewa</h2>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Kamar</dt>
                        <dd class="font-medium text-gray-800">
                            Kamar {{ $order->room->room_number ?? '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Harga / bulan</dt>
                        <dd class="font-medium text-gray-800">
                            Rp {{ number_format($order->room->price ?? 0, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Lama Sewa</dt>
                        <dd class="font-medium text-gray-800">
                            {{ $order->rent_duration }} bulan
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Tanggal Masuk</dt>
                        <dd class="font-medium text-gray-800">
                            {{ optional($order->start_date)->format('d-m-Y') }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Status</dt>
                        <dd>
                            @if($order->status === 'pending')
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-[11px] font-semibold">
                                    MENUNGGU KONFIRMASI
                                </span>
                            @elseif($order->status === 'approved')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-[11px] font-semibold">
                                    DISETUJUI
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-[11px] font-semibold">
                                    DITOLAK
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

        </div>

        {{-- Kolom kanan: lampiran + aksi konfirmasi --}}
        <div class="space-y-6">

            {{-- Foto KTP --}}
            <div class="bg-white rounded-xl shadow border border-gray-100 p-4">
                <h2 class="text-sm font-semibold text-gray-800 mb-3">Foto KTP</h2>
                @if($order->id_photo_path)
                    <a href="{{ asset('storage/'.$order->id_photo_path) }}"
                       target="_blank"
                       class="block border border-gray-200 rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/'.$order->id_photo_path) }}"
                             alt="Foto KTP"
                             class="w-full h-48 object-cover">
                    </a>
                    <p class="mt-1 text-xs text-gray-500">
                        Klik gambar untuk memperbesar.
                    </p>
                @else
                    <p class="text-xs text-gray-500">Tidak ada foto KTP.</p>
                @endif
            </div>

            {{-- Bukti Transfer --}}
            <div class="bg-white rounded-xl shadow border border-gray-100 p-4">
                <h2 class="text-sm font-semibold text-gray-800 mb-3">Bukti Transfer</h2>
                @if($order->transfer_proof_path)
                    <a href="{{ asset('storage/'.$order->transfer_proof_path) }}"
                       target="_blank"
                       class="block border border-gray-200 rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/'.$order->transfer_proof_path) }}"
                             alt="Bukti Transfer"
                             class="w-full h-48 object-cover">
                    </a>
                    <p class="mt-1 text-xs text-gray-500">
                        Klik gambar untuk memperbesar.
                    </p>
                @else
                    <p class="text-xs text-gray-500">Tidak ada bukti transfer.</p>
                @endif
            </div>

            {{-- Form Konfirmasi --}}
            <div class="bg-white rounded-xl shadow border border-gray-100 p-4">
                <h2 class="text-sm font-semibold text-gray-800 mb-3">Konfirmasi Pesanan</h2>

                @if($order->status === 'pending')
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Catatan (opsional, hanya untuk admin)
                            </label>
                            <textarea name="note" rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('note', $order->admin_note ?? '') }}</textarea>
                            @error('note')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" name="status" value="approved"
                                    class="flex-1 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700">
                                Setujui
                            </button>
                            <button type="submit" name="status" value="rejected"
                                    class="flex-1 px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700">
                                Tolak
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-xs text-gray-500 mb-2">
                        Pesanan ini sudah memiliki status final: <strong>{{ strtoupper($order->status) }}</strong>.
                    </p>
                    @if(!empty($order->admin_note))
                        <div class="mt-2">
                            <p class="text-xs font-semibold text-gray-700">Catatan Admin:</p>
                            <p class="text-xs text-gray-600">{{ $order->admin_note }}</p>
                        </div>
                    @endif
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
