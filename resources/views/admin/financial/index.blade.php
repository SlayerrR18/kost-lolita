@extends('layouts.main')

@section('title', 'Manajemen Keuangan')

@push('css')
<style>
    .financial-container {
        padding: 32px;
        background: #f6f8fa;
        min-height: 100vh;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a7f5a;
        margin-bottom: 24px;
    }

    .financial-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        padding: 24px;
    }

    .btn-add {
        background: #1a7f5a;
        color: #fff;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-add:hover {
        background: #156c4a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26,127,90,0.15);
        color: #fff;
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        padding: 16px;
        text-align: left;
        border-bottom: 2px solid #e9eef3;
    }

    .table td {
        padding: 16px;
        border-bottom: 1px solid #e9eef3;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .status-pemasukan {
        background: #dcfce7;
        color: #166534;
    }

    .status-pengeluaran {
        background: #fee2e2;
        color: #991b1b;
    }

    .bukti-image {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 8px;
    }

    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 1rem 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
    }

    #previewImage {
        border-radius: 0 0 16px 16px;
    }

    .form-label.required:after {
        content: "*";
        color: #dc2626;
        margin-left: 4px;
    }

    .form-control, .form-select {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #1a7f5a;
        box-shadow: 0 0 0 3px rgba(26,127,90,0.1);
    }

    .input-group-text {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-right: none;
        border-radius: 8px 0 0 8px;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 8px 8px 0;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .modal-content {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .btn-light {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .btn-icon {
        padding: 6px;
        line-height: 1;
        border-radius: 8px;
        background: transparent;
        border: none;
    }

    .btn-icon i {
        width: 18px;
        height: 18px;
        stroke-width: 2;
    }

    .btn-icon:hover {
        background: #f1f5f9;
    }

    .btn-delete {
        color: #ef4444;
    }

    .btn-delete:hover {
        background: #fee2e2;
    }

    .empty-state {
        padding: 24px;
    }

    .empty-state-icon {
        width: 32px;
        height: 32px;
        color: #94a3b8;
    }

    .empty-state-text {
        color: #64748b;
        margin: 0;
    }
</style>
@endpush

@section('content')
<div class="financial-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Manajemen Keuangan</h1>
        <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
            <i data-feather="plus-circle"></i>
            <span class="ms-2">Tambah Transaksi</span>
        </button>
    </div>

    <div class="financial-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No Kamar</th>
                        <th>Nama Transaksi</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->kost->nomor_kamar }}</td>
                        <td>{{ $transaction->nama_transaksi }}</td>
                        <td>{{ $transaction->tanggal_transaksi->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="status-badge {{ $transaction->status === 'Pemasukan' ? 'status-pemasukan' : 'status-pengeluaran' }}">
                                {{ $transaction->status }}
                            </span>
                        </td>
                        <td>
                            @if($transaction->bukti_pembayaran)
                                <button type="button" class="btn btn-icon" onclick="showImage('{{ Storage::url($transaction->bukti_pembayaran) }}')" title="Lihat Bukti">
                                    <i data-feather="eye"></i>
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-icon btn-delete" onclick="deleteTransaction({{ $transaction->id }})" title="Hapus">
                                <i data-feather="trash-2"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="empty-state">
                                <i data-feather="inbox" class="empty-state-icon mb-2"></i>
                                <p class="empty-state-text">Belum ada data transaksi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Transaction Modal -->
<div class="modal fade" id="addTransactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.financial.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kamar</label>
                        <select name="kost_id" class="form-select" required>
                            <option value="">Pilih Kamar</option>
                            @foreach($kosts as $kost)
                                <option value="{{ $kost->id }}">
                                    Kamar {{ $kost->nomor_kamar }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Transaksi</label>
                        <input type="text" name="nama_transaksi" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total</label>
                        <input type="number" name="total" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Pemasukan">Pemasukan</option>
                            <option value="Pengeluaran">Pengeluaran</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Transaksi</label>
                        <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title d-flex align-items-center">
                    <i data-feather="image" class="me-2"></i>
                    Bukti Transaksi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img src="" id="previewImage" class="img-fluid w-100">
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title d-flex align-items-center text-danger">
                    <i data-feather="alert-triangle" class="me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus transaksi ini?</p>
                <p class="mb-0 text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i data-feather="x" class="me-2"></i>
                    Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i data-feather="trash-2" class="me-2"></i>
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();

        // Form validation
        const form = document.getElementById('transactionForm');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });

        // Reinitialize feather icons when modal opens
        const modal = document.getElementById('addTransactionModal');
        modal.addEventListener('shown.bs.modal', function() {
            feather.replace();
        });

        // Auto hide alerts
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.remove();
            }, 3000);
        });
    });

    let deleteTransactionId = null;

    function showImage(url) {
        document.getElementById('previewImage').src = url;
        new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
    }

    function deleteTransaction(id) {
        deleteTransactionId = id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (deleteTransactionId) {
            fetch(`/admin/financial/${deleteTransactionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus transaksi');
            });
        }
    });

    function initFeather() {
        feather.replace({
            'width': 18,
            'height': 18,
            'stroke-width': 2
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initFeather();
    });
</script>
@endpush
