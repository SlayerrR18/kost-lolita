@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Dashboard Penghuni Kost</h2>

            @if ($contract)
            <div class="mb-6">
                <h3 class="text-lg font-medium">Kontrak Anda</h3>
                <p class="text-sm text-gray-600">Kamar: {{ $contract->room->room_number }}</p>
                <p class="text-sm text-gray-600">Mulai: {{ $contract->start_date->format('d-m-Y') }}</p>
                <p class="text-sm text-gray-600">Berakhir: {{ $contract->end_date->format('d-m-Y') }}</p>
                <p class="text-sm text-gray-600">Status: {{ ucfirst($contract->status) }}</p>
            </div>
            @else
            <p class="text-sm text-gray-600">Anda tidak memiliki kontrak aktif saat ini.</p>
            @endif

            <div>
                <h3 class="text-lg font-medium mb-2">Riwayat Pembayaran</h3>
                <table class="min-w-full border border-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Nominal</th>
                            <th class="px-6 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                        <tr>
                            <td class="px-6 py-4">{{ $payment->created_at->format('d-m-Y') }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ ucfirst($payment->status) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
