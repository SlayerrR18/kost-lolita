@extends('layouts.main')

@section('title', 'Pengeluaran')

@push('css')
<style>
    /* Modern Container */
    .financial-container {
        padding: 2rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    /* Enhanced Header Section */
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

    .btn-add {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .btn-add:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
        color: white;
    }

    /* Card Styles */
    .financial-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* Table Styles */
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

    /* Status Badge */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .status-pengeluaran {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Action Buttons */
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
        color: #64748b;
    }

    .btn-icon i {
        margin: 0;
    }

    .btn-view {
        background: #f1f5f9;
    }

    .btn-view:hover {
        background: #e2e8f0;
        color: #1a7f5a;
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
    }

    .empty-state-icon {
        width: 48px;
        height: 48px;
        color: #94a3b8;
        margin-bottom: 1rem;
    }

    .icon-sm {
        width: 16px;
        height: 16px;
        stroke-width: 2;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .btn-view:hover i,
    .btn-delete:hover i {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }

    .feather {
        width: 16px;
        height: 16px;
        stroke-width: 2;
        stroke: currentColor;
        fill: none;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
</style>
@endpush

@section('content')
<div class="financial-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Pengeluaran</h1>
                <p class="mb-0">Kelola data pengeluaran kost</p>
            </div>
            <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                <i data-feather="plus-circle" class="me-2"></i>
                Tambah Pengeluaran
            </button>
        </div>
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
                            <span class="status-badge status-pengeluaran">
                                {{ $transaction->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($transaction->bukti_pembayaran)
                                <button type="button" class="btn btn-icon btn-view"
                                        onclick="showImage('{{ asset('storage/' . $transaction->bukti_pembayaran) }}')"
                                        title="Lihat Bukti">
                                    <i data-feather="eye"></i>
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-icon btn-delete"
                                    onclick="deleteTransaction({{ $transaction->id }})"
                                    title="Hapus Transaksi">
                                <i data-feather="trash-2"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i data-feather="inbox" class="empty-state-icon"></i>
                                <p class="text-muted mb-0">Belum ada data pengeluaran</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.financial.partials.add-modal')
@include('admin.financial.partials.image-modal')
@include('admin.financial.partials.delete-modal')

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initFeather();

    // Re-run feather.replace when table content changes
    const observer = new MutationObserver(function() {
        initFeather();
    });

    observer.observe(document.querySelector('.table-responsive'), {
        childList: true,
        subtree: true
    });
});

function initFeather() {
    // Clear existing icons first
    document.querySelectorAll('.feather').forEach(icon => icon.remove());

    // Re-initialize icons
    feather.replace({
        'width': 16,
        'height': 16,
        'stroke-width': 2
    });
}

function showImage(url) {
    const img = document.getElementById('previewImage');
    img.src = url;
    new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
}

function deleteTransaction(id) {
    Swal.fire({
        title: 'Hapus Transaksi?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/financial/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', data.message, 'success')
                        .then(() => window.location.reload());
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire('Error!', error.message, 'error');
            });
        }
    });
}
</script>
@endpush
