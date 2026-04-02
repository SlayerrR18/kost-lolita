@extends('layouts.order')

@section('title', 'Pembayaran Pesanan #INV-' . str_pad($order->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('user.orders.show', $order) }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-[#222831]">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Detail Pesanan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-serif font-bold text-[#222831]">Pembayaran Online</h1>
                <p class="text-sm text-gray-500">Lanjutkan pembayaran melalui Midtrans untuk mengamankan kamar.</p>
            </div>
            <span class="inline-flex items-center gap-2 rounded-full bg-[#DFD0B8]/20 px-4 py-2 text-sm font-semibold text-[#222831]">
                Status: <span class="text-[#222831]">{{ ucfirst($order->status) }}</span>
            </span>
        </div>

        <div class="grid gap-6 lg:grid-cols-2 mb-8">
            <div class="rounded-3xl border border-gray-100 bg-gray-50 p-6">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-3">Ringkasan Pesanan</p>
                <div class="space-y-4 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <span>Kamar</span>
                        <span class="font-semibold">Kamar {{ $order->room->room_number ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Durasi</span>
                        <span class="font-semibold">{{ $order->rent_duration }} Bulan</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tanggal Mulai</span>
                        <span class="font-semibold">{{ $order->start_date?->format('d F Y') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Harga per Bulan</span>
                        <span class="font-semibold">Rp {{ number_format($order->room->price ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-gray-100 bg-[#222831] p-6 text-white">
                <p class="text-xs uppercase tracking-wide text-[#DFD0B8] mb-3">Total Pembayaran</p>
                <p class="text-3xl font-serif font-bold">Rp {{ number_format(($order->room->price ?? 0) * $order->rent_duration, 0, ',', '.') }}</p>
                <p class="mt-3 text-sm text-[#DFD0B8]">Gunakan tombol di bawah untuk memilih metode pembayaran Midtrans.</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-3xl border border-gray-100 p-6 bg-white">
                <h2 class="text-lg font-bold text-[#222831] mb-3">Instruksi Pembayaran</h2>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                    <li>Pilih metode pembayaran yang tersedia di halaman berikut.</li>
                    <li>Setelah selesai, status pesanan akan otomatis diperbarui oleh sistem.</li>
                    <li>Jika pembayaran sukses, Anda akan diarahkan kembali ke halaman detail pesanan.</li>
                </ul>
            </div>

            <button id="pay-button" class="w-full rounded-3xl bg-[#222831] py-4 text-lg font-bold text-[#DFD0B8] hover:bg-black transition">
                Bayar Sekarang dengan Midtrans
            </button>
        </div>

        <div class="mt-8 text-xs text-gray-500">
            <p>Pastikan jangan menutup halaman sebelum transaksi selesai.</p>
        </div>
    </div>
</div>

<script src="{{ $snapUrl }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const orderId = '{{ $order->id }}';

    async function finalizePayment() {
        if (!token) {
            return;
        }

        const endpoint = '{{ route('user.orders.payment.complete', $order) }}';
        await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({}),
        });
    }

    document.getElementById('pay-button').addEventListener('click', function () {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: async function (result) {
                await finalizePayment();
                window.location.href = "{{ route('user.orders.show', $order) }}";
            },
            onPending: async function (result) {
                await finalizePayment();
                window.location.href = "{{ route('user.orders.show', $order) }}";
            },
            onError: function (result) {
                alert('Pembayaran gagal. Silakan coba lagi atau pilih metode lain.');
            },
            onClose: function () {
                alert('Pembayaran belum selesai. Anda bisa mencoba lagi nanti.');
            }
        });
    });
</script>
@endsection
