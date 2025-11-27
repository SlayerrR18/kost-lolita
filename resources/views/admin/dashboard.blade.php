@extends('layouts.admin-layout')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Dashboard Admin Kost Lolita</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Total Kamar -->
                <div class="bg-blue-600 text-white p-4 rounded-lg shadow">
                    <h3 class="text-lg font-medium">Total Kamar</h3>
                    {{-- <p class="text-2xl">{{ $totalRooms }}</p> --}}
                </div>

                <!-- Kamar Tersedia -->
                <div class="bg-green-600 text-white p-4 rounded-lg shadow">
                    <h3 class="text-lg font-medium">Kamar Tersedia</h3>
                    {{-- <p class="text-2xl">{{ $availableRooms }}</p> --}}
                </div>

                <!-- Total Penghuni -->
                <div class="bg-yellow-600 text-white p-4 rounded-lg shadow">
                    <h3 class="text-lg font-medium">Total Penghuni</h3>
                    {{-- <p class="text-2xl">{{ $totalUsers }}</p> --}}
                </div>

                <!-- Total Pemasukan -->
                <div class="bg-teal-600 text-white p-4 rounded-lg shadow">
                    <h3 class="text-lg font-medium">Total Pemasukan</h3>
                    {{-- <p class="text-2xl">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p> --}}
                </div>

                <!-- Total Pengeluaran -->
                <div class="bg-red-600 text-white p-4 rounded-lg shadow">
                    <h3 class="text-lg font-medium">Total Pengeluaran</h3>
                    {{-- <p class="text-2xl">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
