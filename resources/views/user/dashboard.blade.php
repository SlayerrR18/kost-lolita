@extends('layouts.user-layout')

@section('title', 'Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-[#222831] mb-6">Selamat Datang, {{ Auth::user()->name }}!</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items
-center">
                    <div class="p-3 bg-[#DFD0B8]/20 rounded-xl text-[#222831] group-hover:bg-[#25477a] group-hover:text-[#DFD0B8] transition-colors">
                        <i class="fa-solid fa-box text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pesanan</p>
                        <h3 class="text-3xl font-bold text-[#222831] mt-2">{{ $contracts ?? '15' }}</h3>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items
-center">
                    <div class="p-3 bg-[#DFD0B8]/20 rounded-xl text-[#222831] group-hover:bg-[#25477a] group-hover:text-[#DFD0B8] transition-colors">
                        <i class="fa-solid fa-bed text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kamar Dipesan</p>
                        <h3 class="text-3xl font-bold text-[#222831] mt-2">{{ $roomsBooked ?? '10' }}</h3>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-[#DFD0B8]/20 rounded-xl text-[#222831] group-hover:bg-[#25477a] group-hover:text-[#DFD0B8] transition-colors">
                        <i class="fa-solid fa-money-bill-wave text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pembayaran</p>
                        <h3 class="text-3xl font-bold text-[#222831] mt-2">Rp {{ number_format($totalPayments ?? 5000000, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
