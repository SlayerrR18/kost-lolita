@extends('layouts.order')

@section('title', 'Status Pesanan - Kost Lolita')

@section('header')
    <h1 class="text-2xl font-bold text-gray-800">
        Status Pesanan Anda
    </h1>
@endsection

@section('content')
<div class="max-w-3xl mx-auto px-6">

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-200 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-6">
        @if($order->status === 'pending')
            <div class="mb-4 rounded-lg bg-yellow-50 border border-yellow-200 px-4 py-3 text-sm text-yellow-800">
                Pesanan Anda sedang menunggu konfirmasi dari admin.
            </div>
        @elseif($order->status === 'approved')
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                Pesanan Anda telah disetujui.
            </div>
        @else
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                Pesanan Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.
            </div>
        @endif

        <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Pesanan</h2>

        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-500">Nomor Pesanan</dt>
                <dd class="font-medium text-gray-800">#{{ $order->id }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Kamar</dt>
                <dd class="font-medium text-gray-800">Kamar {{ $order->room->room_number ?? '-' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Nama</dt>
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
            <div class="flex justify-between">
                <dt class="text-gray-500">Lama Sewa</dt>
                <dd class="font-medium text-gray-800">{{ $order->rent_duration }} bulan</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Tanggal Masuk</dt>
                <dd class="font-medium text-gray-800">
                    {{ optional($order->start_date)->format('d-m-Y') }}
                </dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Status</dt>
                <dd class="font-semibold text-gray-800 text-right">
                    {{ strtoupper($order->status) }}
                </dd>
            </div>
        </dl>
    </div>

    <div class="flex justify-between">
        <a href="{{ route('landing') }}"
           class="text-sm text-gray-600 hover:text-gray-900 underline">
            Kembali ke halaman utama
        </a>

        @if($order->status === 'approved')
            <a href="{{ route('user.dashboard') }}"
               class="px-6 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-accent">
                Pergi ke Dashboard
            </a>
        @endif
    </div>
</div>
@endsection
