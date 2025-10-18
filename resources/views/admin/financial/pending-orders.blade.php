{{-- resources/views/admin/financial/pending-orders.blade.php (Improved V3) --}}
@extends('layouts.admin')

@section('title', 'Konfirmasi Pesanan')

@push('css')
<style>
    /* === Palet & Umum (diselaraskan dengan desain sebelumnya) === */
    :root {
        --primary: #1a7f5a;
        --primary-2: #16c79a;
        --secondary: #f1f5f9;
        --accent: #0f172a;
        --surface: #ffffff;
        --bg: #f8fafc;
        --ink: #1e293b;
        --muted: #64748b;
        --ring: #e2e8f0;
        --danger: #dc2626;
        --success: #16a34a;
        --info: #0ea5e9;
        --warning: #f59e0b;
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 24px;
        --radius-pill: 9999px;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    /* === Layout & Containers === */
    .main-container {
        padding: 2rem;
        background-color: var(--bg);
        min-height: 100vh;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        color: #fff;
        box-shadow: 0 4px 20px rgba(26, 127, 90, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }

    .page-subtitle {
        opacity: 0.9;
        margin-top: 0.5rem;
    }

    /* === Stats Card === */
    .stats-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stats-title {
        color: var(--muted);
        font-weight: 600;
        font-size: .875rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin: 0;
    }

    .stats-value {
        color: var(--ink);
        font-weight: 800;
        font-size: 1.75rem;
        margin-top: .15rem;
    }


    /* === Main Content Card & Table === */
    .card-base {
        background-color: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }

    .table-responsive {
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: var(--bg);
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 1px solid var(--ring);
    }

    .table tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--ring);
    }

    .table tbody tr:hover {
        background-color: #f1f5f9;
    }

    .table .fw-medium {
        color: var(--ink);
    }
    .table .small-text {
        font-size: 0.875rem;
        color: var(--muted);
    }

    /* === Badge & Tombol === */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.8rem;
        border-radius: var(--radius-sm);
        font-weight: 500;
        font-size: 0.875rem;
        white-space: nowrap;
    }

    .badge-status.badge-info { background-color: #e0f2fe; color: #075985; }
    .badge-status.badge-warning { background-color: #fef3c7; color: #92400e; }

    .btn-refresh {
        background-color: var(--secondary);
        color: var(--ink);
        border: none;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-md);
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.25s ease;
        box-shadow: var(--shadow-md);
        text-decoration: none;
    }

    .btn-refresh:hover {
        background-color: #e7eef6;
        transform: translateY(-2px);
    }

    .btn-preview {
        background-color: var(--secondary);
        color: var(--muted);
        border: none;
        border-radius: var(--radius-sm);
        padding: 0.5rem;
        transition: background-color 0.2s;
    }

    .btn-preview:hover {
        background-color: #e2e8f0;
    }

    .btn-action-approve, .btn-action-reject {
        border-radius: var(--radius-md);
        padding: 0.65rem 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-action-approve {
        background-color: var(--success);
        color: white;
    }

    .btn-action-approve:hover {
        background-color: #15803d;
        transform: translateY(-2px);
        color: white;
    }

    .btn-action-reject {
        background-color: var(--danger);
        color: white;
    }

    .btn-action-reject:hover {
        background-color: #b91c1c;
        transform: translateY(-2px);
        color: white;
    }

    .modal-content { border-radius: var(--radius-md); border: none; }
    .modal-header { border-bottom: 1px solid var(--ring); padding: 1.25rem 1.5rem; }
    .modal-body { padding: 1.5rem; }
    .modal-footer { border-top: 1px solid var(--ring); padding: 1.25rem 1.5rem; }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--muted);
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }

    .modal-icon.text-primary { color: var(--primary); }
</style>
@endpush

@section('content')
<div class="main-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title">Konfirmasi Pesanan</h1>
                <p class="page-subtitle">Kelola pesanan yang menunggu konfirmasi</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-refresh" onclick="window.location.reload()">
                    <i data-feather="refresh-cw" aria-hidden="true"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <div class="stats-card mb-4">
        <div class="stats-title">Jumlah Pesanan Pending</div>
        <div class="stats-value">{{ $pendingOrders->count() }}</div>
    </div>

    <div class="card-base">
        <div class="card-body p-0">
            @if($pendingOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No Kamar</th>
                                <th>Nama Pemesan</th>
                                <th>Kontak</th>
                                <th class="text-center">Bukti</th>
                                <th>Tipe</th>
                                <th>Waktu</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingOrders as $order)
                            <tr data-order-id="{{ $order->id }}">
                                <td>
                                    <div class="fw-medium">Kamar {{ $order->kost->nomor_kamar ?? '-' }}</div>
                                    @if($order->kost)
                                        <div class="small-text">
                                            Rp {{ number_format($order->kost->harga, 0, ',', '.') }}/bulan
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $order->name }}</div>
                                    <div class="small-text">{{ $order->alamat }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i data-feather="mail" class="small-text" style="width:14px"></i>
                                        <span>{{ $order->email }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <i data-feather="phone" class="small-text" style="width:14px"></i>
                                        <span>{{ $order->phone }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($order->bukti_pembayaran)
                                        <button class="btn btn-preview"
                                                data-bs-toggle="modal"
                                                data-bs-target="#imagePreviewModal"
                                                data-image-url="{{ asset('storage/' . $order->bukti_pembayaran) }}"
                                                aria-label="Lihat bukti pembayaran">
                                            <i data-feather="image"></i>
                                        </button>
                                    @else
                                        <span class="small-text">Belum Ada</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-status {{ $order->is_extension ? 'badge-warning' : 'badge-info' }}">
                                        {{ $order->is_extension ? 'Perpanjangan' : 'Baru' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $order->created_at->format('d M Y') }}</div>
                                    <div class="small-text">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button"
                                                class="btn btn-action-approve"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmModal"
                                                data-order-id="{{ $order->id }}"
                                                data-order-type="{{ $order->is_extension ? 'extension' : 'new' }}"
                                                aria-label="Terima pesanan">
                                            <i data-feather="check"></i> Terima
                                        </button>
                                        <button type="button"
                                                class="btn btn-action-reject"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectModal"
                                                data-order-id="{{ $order->id }}"
                                                aria-label="Tolak pesanan">
                                            <i data-feather="x"></i> Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i data-feather="inbox" class="empty-state-icon" aria-hidden="true"></i>
                    <h5 class="mt-4">Tidak Ada Pesanan Pending</h5>
                    <p class="text-muted mb-0">Semua pesanan sudah dikonfirmasi.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modals --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">Preview Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <img src="" id="previewImage" class="img-fluid w-100 rounded-bottom" alt="Bukti Pembayaran">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i data-feather="info" class="me-2"></i> Konfirmasi Pesanan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i data-feather="help-circle" class="modal-icon text-primary mb-4" aria-hidden="true"></i>
                <p id="confirmMessage">Apakah Anda yakin ingin mengkonfirmasi pesanan ini? Akun pengguna akan dibuat secara otomatis.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-primary" id="confirmActionBtn">
                    <i data-feather="check"></i> Ya, Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i data-feather="check-circle" class="me-2"></i> Pesanan Dikonfirmasi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i data-feather="user-check" class="modal-icon text-success mb-4" aria-hidden="true"></i>
                <h5 class="mb-3">Pesanan Berhasil Dikonfirmasi</h5>
                <p class="text-muted">Detail pesanan dan akun pengguna telah dibuat.</p>

                <div class="order-details-card">
                    <h6>Detail Pesanan:</h6>
                    <div class="detail-item">
                        <span class="detail-label">Nama Pemesan</span>
                        <strong id="confirmName">-</strong>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <strong id="confirmEmail">-</strong>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">No. Kamar</span>
                        <strong id="confirmRoom">-</strong>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Durasi Sewa</span>
                        <strong><span id="confirmDuration">-</span> Bulan</strong>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Masuk</span>
                        <strong id="confirmCheckIn">-</strong>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Keluar</span>
                        <strong id="confirmCheckOut">-</strong>
                    </div>
                </div>

                <div class="alert alert-info mt-3" role="alert">
                    <div class="d-flex align-items-start">
                        <i data-feather="info" class="me-2 mt-1"></i>
                        <div>
                            <small class="d-block fw-medium">Password default telah dibuat</small>
                            <small class="text-muted">Silakan atur ulang password di menu Manajemen Akun jika diperlukan</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('admin.account.index') }}" class="btn btn-secondary">
                    <i data-feather="users"></i> Ke Manajemen Akun
                </a>
                <button type="button" class="btn btn-refresh" onclick="window.location.reload()">
                    <i data-feather="refresh-cw"></i> Muat Ulang
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i data-feather="x-circle" class="me-2"></i> Tolak Pesanan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i data-feather="alert-triangle" class="modal-icon text-danger mb-4" aria-hidden="true"></i>
                <h5 class="mb-3">Konfirmasi Penolakan</h5>
                <p class="mb-0">Apakah Anda yakin ingin menolak pesanan ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmRejectBtn">
                    <i data-feather="trash-2"></i> Ya, Tolak
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
(() => {
    'use strict';

    let currentOrderId = null;

    const $ = s => document.querySelector(s);
    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    function formatID(iso) {
        if (!iso) return '-';
        try {
            return new Date(iso + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        } catch { return iso; }
    }

    function setLoading(btn, on, text = 'Memproses...') {
        if (!btn) return;
        if (on) {
            btn.dataset._inner = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>${text}`;
        } else {
            btn.disabled = false;
            btn.innerHTML = btn.dataset._inner || btn.innerHTML;
        }
    }

    async function processOrderAction(url, method = 'POST', btn) {
        setLoading(btn, true);
        try {
            const res = await fetch(url, {
                method: method,
                headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || 'Terjadi kesalahan saat memproses permintaan.');
            }
            return data;
        } catch (err) {
            alert(err.message);
            return null;
        } finally {
            setLoading(btn, false);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        feather.replace();

        // Handle Image Preview Modal
        $('#imagePreviewModal')?.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const imageUrl = button.getAttribute('data-image-url');
            const modalImage = this.querySelector('#previewImage');
            if (modalImage) {
                modalImage.src = imageUrl;
            }
        });

        // Handle Confirm Modal
        $('#confirmModal')?.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            currentOrderId = button.getAttribute('data-order-id');
            const orderType = button.getAttribute('data-order-type');

            const title = $('#confirmModalLabel');
            const message = $('#confirmMessage');

            if (orderType === 'extension') {
                title.innerHTML = `<i data-feather="info" class="me-2"></i> Konfirmasi Perpanjangan`;
                message.textContent = 'Apakah Anda yakin ingin mengkonfirmasi perpanjangan kontrak ini?';
            } else {
                title.innerHTML = `<i data-feather="info" class="me-2"></i> Konfirmasi Pesanan`;
                message.textContent = 'Apakah Anda yakin ingin mengkonfirmasi pesanan ini? Akun pengguna akan dibuat secara otomatis.';
            }
            feather.replace();
        });

        // Handle Reject Modal
        $('#rejectModal')?.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            currentOrderId = button.getAttribute('data-order-id');
        });

        // Process Confirmation Action
        $('#confirmActionBtn')?.addEventListener('click', async function () {
            if (!currentOrderId) return;
            const data = await processOrderAction(`/admin/financial/orders/${currentOrderId}/confirm`, 'POST', this);

            if (data) {
                const orderData = data.data;
                $('#confirmName').textContent = orderData.name ?? '-';
                $('#confirmEmail').textContent = orderData.email ?? '-';
                $('#confirmRoom').textContent = orderData.room_number ?? '-';
                $('#confirmDuration').textContent = orderData.duration ?? '-';
                $('#confirmCheckIn').textContent = formatID(orderData.tanggal_masuk);
                $('#confirmCheckOut').textContent = formatID(orderData.tanggal_keluar);

                bootstrap.Modal.getInstance($('#confirmModal')).hide();
                new bootstrap.Modal($('#successModal')).show();

                const row = document.querySelector(`[data-order-id="${currentOrderId}"]`);
                if (row) row.remove();
            }
        });

        // Process Reject Action
        $('#confirmRejectBtn')?.addEventListener('click', async function () {
            if (!currentOrderId) return;
            const data = await processOrderAction(`/admin/financial/orders/${currentOrderId}/reject`, 'POST', this);

            if (data) {
                const row = document.querySelector(`[data-order-id="${currentOrderId}"]`);
                if (row) row.remove();
                bootstrap.Modal.getInstance($('#rejectModal')).hide();
            }
        });
    });
})();
</script>
@endpush
