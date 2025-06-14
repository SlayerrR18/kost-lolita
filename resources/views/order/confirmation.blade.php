@extends('layouts.order')
@section('title', 'Konfirmasi Pesanan')

@section('content')
<div class="confirmation-container">
    <div class="confirmation-card">
        <div class="status-icon pending">
            <i data-feather="clock"></i>
        </div>

        <h1>Pesanan Sedang Diproses</h1>
        <p class="message">Terima kasih telah memesan kamar di Kost Lolita. Admin akan memverifikasi pembayaran Anda dalam waktu 1x24 jam.</p>

        <div class="order-details">
            <h2>Detail Pesanan</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">Nomor Pesanan</span>
                    <span class="value">{{ $order->id }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Kamar</span>
                    <span class="value">Kamar {{ $order->kost->nomor_kamar }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Harga</span>
                    <span class="value">Rp {{ number_format($order->kost->harga, 0, ',', '.') }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Status</span>
                    <span class="value status-badge">Menunggu Konfirmasi</span>
                </div>
            </div>
        </div>

        <div class="next-steps">
            <h3>Langkah Selanjutnya</h3>
            <ol>
                <li>Admin akan memverifikasi pembayaran Anda</li>
                <li>Jika pembayaran valid, akun akan dibuat otomatis</li>
                <li>Informasi login akan dikirim ke email Anda</li>
                <li>Anda dapat login dan mengakses informasi kamar</li>
            </ol>
        </div>

        <div class="action-buttons">
            <a href="{{ route('welcome') }}" class="btn-secondary">
                <i data-feather="home"></i>
                Kembali ke Beranda
            </a>
            <a href="https://wa.me/6281234567890" target="_blank" class="btn-primary">
                <i data-feather="message-circle"></i>
                Hubungi Admin
            </a>
        </div>
    </div>
</div>

@push('css')
<style>
.confirmation-container {
    max-width: 800px;
    margin: 3rem auto;
    padding: 0 1.5rem;
}

.confirmation-card {
    background: white;
    border-radius: 20px;
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}

.status-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0fdf4;
}

.status-icon.pending svg {
    width: 40px;
    height: 40px;
    color: #1a7f5a;
}

.confirmation-card h1 {
    color: #1e293b;
    font-size: 1.875rem;
    margin-bottom: 1rem;
}

.message {
    color: #64748b;
    margin-bottom: 3rem;
}

.order-details {
    text-align: left;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.order-details h2 {
    color: #1e293b;
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
}

.info-grid {
    display: grid;
    gap: 1.5rem;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item .label {
    color: #64748b;
    font-size: 0.875rem;
}

.info-item .value {
    color: #1e293b;
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #1a7f5a;
    color: white !important;
    border-radius: 20px;
    font-size: 0.875rem;
}

.next-steps {
    text-align: left;
    margin: 2rem 0;
    padding: 0 1rem;
}

.next-steps h3 {
    color: #1e293b;
    margin-bottom: 1rem;
}

.next-steps ol {
    color: #475569;
    padding-left: 1.25rem;
    line-height: 1.8;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 3rem;
}

.btn-primary,
.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-primary {
    background: #1a7f5a;
    color: white;
}

.btn-secondary {
    background: #f1f5f9;
    color: #475569;
}

.btn-primary:hover {
    background: #15664a;
    transform: translateY(-1px);
}

.btn-secondary:hover {
    background: #e2e8f0;
    transform: translateY(-1px);
}

@media (max-width: 640px) {
    .confirmation-container {
        margin: 2rem auto;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-direction: column;
    }
}
</style>
@endpush
