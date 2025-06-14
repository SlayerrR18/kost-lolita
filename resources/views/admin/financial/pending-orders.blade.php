@extends('layouts.main')

@section('title', 'Konfirmasi Pesanan')

@section('content')
<div class="main-content">
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Konfirmasi Pesanan</h3>
                <p class="text-muted">Kelola pesanan yang menunggu konfirmasi</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            @if($pendingOrders->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-nowrap">No Kamar</th>
                            <th>Nama Pemesan</th>
                            <th>Kontak</th>
                            <th class="text-center">Bukti Pembayaran</th>
                            <th>Waktu Pemesanan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingOrders as $order)
                        <tr>
                            <td>
                                <span class="fw-medium">Kamar {{ $order->kost->nomor_kamar }}</span>
                                <div class="small text-muted">Rp {{ number_format($order->kost->harga, 0, ',', '.') }}/bulan</div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $order->name }}</div>
                                <div class="small text-muted">{{ $order->alamat }}</div>
                            </td>
                            <td>
                                <div><i data-feather="mail" class="icon-sm me-2"></i>{{ $order->email }}</div>
                                <div><i data-feather="phone" class="icon-sm me-2"></i>{{ $order->phone }}</div>
                            </td>
                            <td class="text-center">
                                @if($order->bukti_pembayaran)
                                    <button type="button" class="btn btn-sm btn-light" onclick="showImage('{{ Storage::url($order->bukti_pembayaran) }}')">
                                        <i data-feather="image" class="me-1"></i>
                                        Lihat Bukti
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $order->created_at->format('d M Y') }}</div>
                                <div class="small text-muted">{{ $order->created_at->format('H:i') }}</div>
                                <div class="small text-muted">{{ $order->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button"
                                            class="btn btn-sm btn-success d-flex align-items-center gap-2"
                                            onclick="showConfirmModal('{{ $order->id }}')">
                                        <i data-feather="check"></i>
                                        Terima
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-danger d-flex align-items-center gap-2"
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
            <div class="text-center py-5">
                <i data-feather="inbox" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                <h5 class="text-muted mb-0">Tidak ada pesanan yang menunggu konfirmasi</h5>
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
                <img src="" id="previewImage" class="img-fluid w-100 rounded">
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

@push('css')
<style>
.icon-sm {
    width: 16px;
    height: 16px;
    stroke-width: 2.5px;
}

.table > :not(caption) > * > * {
    padding: 1rem;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.img-thumbnail {
    transition: transform 0.2s;
}

.img-thumbnail:hover {
    transform: scale(1.05);
}

.modal-header .btn-close {
    margin: -0.5rem -0.5rem -0.5rem auto;
}

.table-borderless > tbody > tr > td {
    padding: 0.25rem 0;
}

.alert-info {
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

.modal-content {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal .btn-close {
    background-size: 0.8em;
}

.spinner-border {
    width: 1rem;
    height: 1rem;
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
            // Format dates
            const tanggalMasuk = new Date(data.data.tanggal_masuk).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            const tanggalKeluar = new Date(data.data.tanggal_keluar).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            // Update success modal content
            document.getElementById('confirmName').textContent = data.data.name;
            document.getElementById('confirmEmail').textContent = data.data.email;
            document.getElementById('confirmRoom').textContent = data.data.room_number;
            document.getElementById('confirmDuration').textContent = data.data.duration;
            document.getElementById('confirmCheckIn').textContent = tanggalMasuk;
            document.getElementById('confirmCheckOut').textContent = tanggalKeluar;

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
    fetch(`/admin/financial/orders/${orderId}/reject`, {
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
            location.reload();
        } else {
            alert('Gagal menolak pesanan: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menolak pesanan');
    });
}

// Initialize Feather Icons
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
});
</script>
@endpush
