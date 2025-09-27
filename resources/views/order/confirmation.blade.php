@extends('layouts.order')
@section('title', 'Konfirmasi Pesanan')

@push('css')
<style>
    /* === Palet & Umum === */
    :root {
        --primary: #1a7f5a; --primary-light: #16c79a; --bg: #f8fafc; --ink: #1e293b;
        --muted: #64748b; --line: #e2e8f0; --card: #ffffff; --success: #16a34a; --warning: #f59e0b;
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
        --radius-md: 12px; --radius-lg: 24px;
    }
    body { font-family: 'Poppins', sans-serif; background-color: var(--bg); }

    /* [UPGRADE] Layout Utama */
    .confirmation-wrapper {
        max-width: 1100px;
        margin: 4rem auto;
        padding: 0 1.5rem;
    }
    .confirmation-grid {
        display: grid;
        grid-template-columns: 1fr 350px; /* Kolom utama & sidebar */
        gap: 2rem;
        align-items: flex-start;
    }

    /* [UPGRADE] Konten Utama di Kiri */
    .main-content {
        background: var(--card);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        padding: 2.5rem;
        text-align: center;
    }

    /* [UPGRADE] Ikon Status dengan Animasi */
    .status-icon-wrapper {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
    }
    .status-icon {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        position: relative;
    }
    .status-icon.pending { background-color: #fffbeb; color: #b45309; }
    .status-icon svg { width: 40px; height: 40px; }

    /* Animasi denyut untuk ikon pending */
    .status-icon::before, .status-icon::after {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        z-index: 1;
    }
    .status-icon.pending::before {
        background-color: #fef3c7;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(0.95); opacity: 0.7; }
        70% { transform: scale(1.2); opacity: 0; }
        100% { transform: scale(0.95); opacity: 0; }
    }

    .main-content h1 {
        color: var(--ink);
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    .main-content .message {
        color: var(--muted);
        max-width: 500px;
        margin: 0 auto 2.5rem;
        line-height: 1.7;
    }

    /* [UPGRADE] Desain Detail Pesanan */
    .order-summary {
        text-align: left;
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        margin-bottom: 2rem;
    }
    .summary-header {
        padding: 1rem 1.5rem;
        background-color: var(--bg-soft);
        border-bottom: 1px solid var(--line);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .summary-header .label { font-size: 0.9rem; color: var(--muted); }
    .summary-header .order-id { font-weight: 600; color: var(--ink); display: flex; align-items: center; gap: 0.5rem; }
    .btn-copy {
        background: none; border: none; cursor: pointer;
        color: var(--muted); padding: 0.25rem;
        display: inline-flex; align-items: center;
    }
    .btn-copy:hover { color: var(--primary); }

    .summary-body {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .room-visual {
        width: 80px; height: 80px;
        border-radius: var(--radius-md);
        background-color: var(--bg-soft);
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }
    .room-visual i { color: var(--primary); width: 36px; height: 36px; }

    .room-details .value { font-weight: 600; color: var(--ink); }
    .room-details .label { font-size: 0.9rem; color: var(--muted); }

    .action-buttons a {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.75rem 1.5rem; border-radius: var(--radius-md);
        font-weight: 500; text-decoration: none; transition: all 0.2s;
    }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #15664a; transform: translateY(-2px); }
    .btn-secondary { background: var(--bg-soft); color: var(--ink-light); }
    .btn-secondary:hover { background: var(--line); transform: translateY(-2px); }


    /* [UPGRADE] Sidebar di Kanan */
    .sidebar-content {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    .timeline-card, .help-card {
        background: var(--card);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        padding: 1.5rem;
    }
    .sidebar-content h3 {
        font-size: 1.2rem;
        color: var(--ink);
        margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .sidebar-content h3 i { color: var(--primary); }

    /* [UPGRADE] Timeline "Langkah Selanjutnya" */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background: var(--line);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .timeline-item:last-child { margin-bottom: 0; }
    .timeline-marker {
        position: absolute;
        left: -2rem;
        top: 0;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--bg);
        border: 2px solid var(--primary);
        color: var(--primary);
        font-size: 0.8rem;
        font-weight: 700;
        display: grid;
        place-items: center;
    }
    .timeline-item.completed .timeline-marker { background: var(--primary); color: white; }
    .timeline-body .title { font-weight: 600; color: var(--ink); }
    .timeline-body .description { font-size: 0.9rem; color: var(--muted); }

    /* Kartu Bantuan */
    .help-card p {
        color: var(--muted);
        margin-bottom: 1rem;
        line-height: 1.6;
    }
    .help-card .btn-whatsapp {
        display: flex;
        width: 100%;
        justify-content: center;
        background: #25d366;
        color: white;
    }
     .help-card .btn-whatsapp:hover {
        background: #1ebe58;
     }

    @media (max-width: 992px) {
        .confirmation-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="confirmation-wrapper">
    <div class="confirmation-grid">

        <div class="main-content">
            <div class="status-icon-wrapper">
                <div class="status-icon pending">
                    <i data-feather="clock"></i>
                </div>
            </div>
            <h1>Pesanan Anda Sedang Diproses</h1>
            <p class="message">Terima kasih! Kami telah menerima pesanan Anda dan akan segera memverifikasi pembayaran. Anda akan menerima notifikasi email setelah pesanan dikonfirmasi.</p>

            <div class="order-summary">
                <div class="summary-header">
                    <span class="label">NOMOR PESANAN</span>
                    <span class="order-id" id="orderId">{{ $order->id }}
                        <button class="btn-copy" onclick="copyToClipboard()" title="Salin ID">
                            <i data-feather="copy" id="copyIcon"></i>
                        </button>
                    </span>
                </div>
                <div class="summary-body">
                    <div class="room-visual">
                        <i data-feather="home"></i>
                    </div>
                    <div class="room-details">
                        <span class="value">Kamar No. {{ $order->kost->nomor_kamar }}</span>
                        <span class="label">Rp {{ number_format($order->kost->harga, 0, ',', '.') }} / bulan</span>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('home') }}" class="btn-secondary">Kembali ke Beranda</a>
            </div>
        </div>

        <div class="sidebar-content">
            <div class="timeline-card">
                <h3><i data-feather="git-commit"></i> Langkah Selanjutnya</h3>
                <div class="timeline">
                    <div class="timeline-item completed">
                        <div class="timeline-marker">1</div>
                        <div class="timeline-body">
                            <div class="title">Pembayaran Diterima</div>
                            <div class="description">Kami sedang memverifikasi bukti pembayaran Anda.</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker">2</div>
                        <div class="timeline-body">
                            <div class="title">Pesanan Dikonfirmasi</div>
                            <div class="description">Setelah terverifikasi, akun Anda akan aktif.</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker">3</div>
                        <div class="timeline-body">
                            <div class="title">Akses Akun Anda</div>
                            <div class="description">Informasi login akan dikirim melalui email.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="help-card">
                <h3><i data-feather="help-circle"></i> Butuh Bantuan?</h3>
                <p>Jika ada pertanyaan atau pesanan Anda belum dikonfirmasi lebih dari 1x24 jam, jangan ragu untuk menghubungi kami.</p>
                <a href="https://wa.me/6281238036180" target="_blank" class="action-buttons btn-whatsapp">
                    <i data-feather="message-circle"></i> Hubungi via WhatsApp
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('js')
<script>
function copyToClipboard() {
    const orderId = document.getElementById('orderId').childNodes[0].textContent.trim();
    navigator.clipboard.writeText(orderId).then(() => {
        const copyIcon = document.getElementById('copyIcon');
        // Ganti ikon menjadi centang
        copyIcon.outerHTML = `<i data-feather="check" id="copyIcon" style="color: var(--success);"></i>`;
        feather.replace();

        // Kembalikan ikon setelah 2 detik
        setTimeout(() => {
            const currentIcon = document.getElementById('copyIcon');
            currentIcon.outerHTML = `<i data-feather="copy" id="copyIcon"></i>`;
            feather.replace();
        }, 2000);
    }).catch(err => {
        console.error('Gagal menyalin: ', err);
    });
}
</script>
@endpush
