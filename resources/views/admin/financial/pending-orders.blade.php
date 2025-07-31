@extends('layouts.main')

@section('title', 'Konfirmasi Pesanan')

@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Konfirmasi Pesanan</h1>
                <p class="mb-0">Kelola pesanan yang menunggu konfirmasi</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light" onclick="window.location.reload()">
                    <i data-feather="refresh-cw" class="me-2"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if($pendingOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No Kamar</th>
                                <th>Nama Pemesan</th>
                                <th>Kontak</th>
                                <th class="text-center">Bukti</th>
                                <th>Waktu</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingOrders as $order)
                            <tr>
                                <td>
                                    <div class="fw-medium">Kamar {{ $order->kost->nomor_kamar }}</div>
                                    <div class="small text-muted">
                                        Rp {{ number_format($order->kost->harga, 0, ',', '.') }}/bulan
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $order->name }}</div>
                                    <div class="small text-muted">{{ $order->alamat }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i data-feather="mail" class="text-muted" style="width:14px"></i>
                                        <span>{{ $order->email }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <i data-feather="phone" class="text-muted" style="width:14px"></i>
                                        <span>{{ $order->phone }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($order->bukti_pembayaran)
                                        <button type="button"
                                                class="btn btn-sm btn-light btn-action"
                                                onclick="showImage('{{ asset('storage/' . $order->bukti_pembayaran) }}')">
                                            <i data-feather="file-text"></i>
                                            Lihat
                                        </button>
                                    @else
                                        <span class="badge bg-warning">Belum Ada</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $order->created_at->format('d M Y') }}</div>
                                    <div class="small text-muted">{{ $order->created_at->format('H:i') }}</div>
                                    <div class="small text-muted">{{ $order->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button"
                                                class="btn btn-sm btn-success btn-action"
                                                onclick="showConfirmModal('{{ $order->id }}')">
                                            <i data-feather="check"></i>
                                            Terima
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-danger btn-action"
                                                onclick="rejectOrder('{{ $order->id }}')">
                                            <i data-feather="x"></i>
                                            Tolak
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
                    <i data-feather="inbox" class="empty-state-icon"></i>
                    <h5>Tidak Ada Pesanan Pending</h5>
                    <p class="text-muted mb-0">Semua pesanan sudah dikonfirmasi</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title">Preview Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img src="" id="previewImage" class="img-fluid w-100 rounded preview-image">
            </div>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-bottom bg-primary text-white">
                <h5 class="modal-title">
                    <i data-feather="info" class="me-2"></i>
                    Konfirmasi Pesanan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="text-primary mb-4">
                    <i data-feather="help-circle" style="width: 64px; height: 64px;"></i>
                </div>
                <h5 class="mb-3">Konfirmasi Pesanan</h5>
                <p class="mb-0">Apakah Anda yakin ingin mengkonfirmasi pesanan ini? Akun pengguna akan dibuat secara otomatis.</p>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i data-feather="x" class="me-2"></i>
                    Batal
                </button>
                <button type="button" class="btn btn-primary" onclick="processConfirmation()">
                    <i data-feather="check" class="me-2"></i>
                    Ya, Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-bottom bg-success text-white">
                <h5 class="modal-title">
                    <i data-feather="check-circle" class="me-2"></i>
                    Pesanan Dikonfirmasi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="text-success mb-3">
                        <i data-feather="user-check" style="width: 64px; height: 64px;"></i>
                    </div>
                    <h5 class="mb-3">Pesanan Berhasil Dikonfirmasi</h5>
                </div>

                <div class="card border-0 bg-light mb-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Detail Pesanan:</h6>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td style="width: 140px"><small class="text-muted">Nama Pemesan</small></td>
                                    <td><strong id="confirmName">-</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Email</small></td>
                                    <td><strong id="confirmEmail">-</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">No. Kamar</small></td>
                                    <td><strong id="confirmRoom">-</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Durasi Sewa</small></td>
                                    <td><strong><span id="confirmDuration">-</span> Bulan</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Tanggal Masuk</small></td>
                                    <td><strong id="confirmCheckIn">-</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Tanggal Keluar</small></td>
                                    <td><strong id="confirmCheckOut">-</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 mb-0">
                    <div class="d-flex">
                        <i data-feather="info" class="me-2"></i>
                        <div>
                            <small class="d-block fw-medium">Password default telah dibuat</small>
                            <small class="text-muted">Silakan atur ulang password di menu Manajemen Akun</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <a href="{{ route('admin.account.index') }}" class="btn btn-primary">
                    <i data-feather="users" class="me-2"></i>
                    Ke Manajemen Akun
                </a>
                <button type="button" class="btn btn-light" onclick="window.location.reload()">
                    <i data-feather="refresh-cw" class="me-2"></i>
                    Muat Ulang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Confirmation Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-bottom bg-danger text-white">
                <h5 class="modal-title">
                    <i data-feather="x-circle" class="me-2"></i>
                    Tolak Pesanan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-4">
                    <i data-feather="alert-triangle" style="width: 64px; height: 64px;"></i>
                </div>
                <h5 class="mb-3">Konfirmasi Penolakan</h5>
                <p class="mb-0">Apakah Anda yakin ingin menolak pesanan ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i data-feather="x" class="me-2"></i>
                    Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmReject">
                    <i data-feather="trash-2" class="me-2"></i>
                    Ya, Tolak
                </button>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    /* Modern Container */
    .main-content {
        padding: 2rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    /* Enhanced Header */
    .page-header {
        background: linear-gradient(135deg, #1a7f5a 0%, #16c79a 100%);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 4px 20px rgba(26, 127, 90, 0.15);
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }

    /* Card Styles */
    .card {
        border-radius: 16px;
        overflow: hidden;
    }

    /* Table Improvements */
    .table {
        margin: 0;
    }

    .table th {
        background: #f8fafc;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background: #f1f5f9;
    }

    /* Action Buttons */
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-action i {
        width: 16px;
        height: 16px;
    }

    .btn-success {
        background: #16a34a;
        border-color: #16a34a;
    }

    .btn-success:hover {
        background: #15803d;
        border-color: #15803d;
    }

    .btn-danger {
        background: #dc2626;
        border-color: #dc2626;
    }

    .btn-danger:hover {
        background: #b91c1c;
        border-color: #b91c1c;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        color: #94a3b8;
        margin-bottom: 1.5rem;
    }

    /* Modal Improvements */
    .modal-content {
        border-radius: 16px;
        border: none;
    }

    .modal-header {
        padding: 1.5rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        padding: 1.5rem;
    }

    /* Preview Image */
    .preview-image {
        border-radius: 12px;
        width: 100%;
        height: auto;
        transition: transform 0.3s ease;
    }

    .preview-image:hover {
        transform: scale(1.02);
    }

    /* Status Badge */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .status-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    /* Info Cards */
    .info-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
    }

    .info-label {
        color: #64748b;
        font-size: 0.875rem;
    }

    .info-value {
        font-weight: 600;
        color: #1e293b;
    }
</style>
@endpush

@push('js')
<script>
let currentOrderId = null;

function showImage(url) {
    document.getElementById('previewImage').src = url;
    new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
}

function showConfirmModal(orderId) {
    currentOrderId = orderId;
    new bootstrap.Modal(document.getElementById('confirmModal')).show();
}

function processConfirmation() {
    if (!currentOrderId) return;

    // Show loading state
    const confirmBtn = document.querySelector('#confirmModal .btn-primary');
    const originalContent = confirmBtn.innerHTML;
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Memproses...`;

    fetch(`/admin/financial/orders/${currentOrderId}/confirm`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Format dates properly
            const tanggalMasuk = new Date(data.data.tanggal_masuk);
            const tanggalKeluar = new Date(data.data.tanggal_keluar);

            const options = {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                timeZone: 'Asia/Jakarta'
            };

            // Update success modal content
            document.getElementById('confirmName').textContent = data.data.name;
            document.getElementById('confirmEmail').textContent = data.data.email;
            document.getElementById('confirmRoom').textContent = data.data.room_number;
            document.getElementById('confirmDuration').textContent = data.data.duration;
            document.getElementById('confirmCheckIn').textContent = tanggalMasuk.toLocaleDateString('id-ID', options);
            document.getElementById('confirmCheckOut').textContent = tanggalKeluar.toLocaleDateString('id-ID', options);

            // Hide confirm modal and show success modal
            bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
            new bootstrap.Modal(document.getElementById('successModal')).show();
        } else {
            alert('Gagal mengkonfirmasi pesanan: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengkonfirmasi pesanan');
    })
    .finally(() => {
        // Reset button state
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = originalContent;
    });
}

function rejectOrder(orderId) {
    currentOrderId = orderId;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

document.getElementById('confirmReject').addEventListener('click', function() {
    if (!currentOrderId) return;

    // Show loading state
    const rejectBtn = document.querySelector('#rejectModal .btn-danger');
    const originalContent = rejectBtn.innerHTML;
    rejectBtn.disabled = true;
    rejectBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Memproses...`;

    fetch(`/admin/financial/orders/${currentOrderId}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        } else {
            alert('Gagal menolak pesanan: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menolak pesanan');
    })
    .finally(() => {
        // Reset button state
        rejectBtn.disabled = false;
        rejectBtn.innerHTML = originalContent;
    });
});

// Initialize Feather Icons
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
});
</script>
@endpush
