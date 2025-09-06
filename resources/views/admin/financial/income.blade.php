@extends('layouts.main')

@section('title', 'Pemasukan')

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
        --warning: #e0f2fe;
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 24px;
    }

    body {
        font-family: 'Poppins', sans-serif;
        color: var(--ink);
    }

    /* === Layout & Containers === */
    .financial-wrap {
        min-height: 100vh;
        background: var(--bg);
        padding: 2rem;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 4px 20px rgba(26, 127, 90, .15);
        margin-bottom: 2rem;
    }

    .page-title {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 700;
    }

    .subtext {
        opacity: .9;
        margin-top: .5rem;
    }

    .btn-primary-ghost {
        background: rgba(255, 255, 255, .15);
        color: #fff;
        border: 2px solid rgba(255, 255, 255, .25);
        padding: .75rem 1.5rem;
        border-radius: var(--radius-md);
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        transition: all .2s ease;
    }

    .btn-primary-ghost:hover {
        background: rgba(255, 255, 255, .25);
        color: #fff;
        transform: translateY(-1px);
    }

    @media (max-width: 576px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .btn-primary-ghost {
            width: 100%;
            justify-content: center;
        }
    }

    /* === Stat Cards === */
    .stat-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        display: flex;
        flex-direction: column;
    }

    .stat-title {
        color: var(--muted);
        font-size: .875rem;
        font-weight: 600;
        margin: 0 0 .5rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--ink);
        margin: 0;
    }

    .stat-foot {
        display: flex;
        align-items: center;
        gap: .25rem;
        font-size: .85rem;
        margin-top: .75rem;
    }

    .stat-foot.up { color: var(--success); }
    .stat-foot.down { color: var(--danger); }
    .stat-foot.muted { color: var(--muted); }

    /* === Table Card & Toolbar === */
    .card-base {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }

    .table-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 1.5rem 0;
    }

    .filter-inline .form-control {
        border-radius: var(--radius-md);
        border: 1px solid var(--ring);
        padding: .5rem 1rem;
        font-size: .875rem;
        color: var(--ink);
    }

    .table-responsive {
        max-height: 64vh;
        overflow: auto;
    }

    .table {
        margin: 0;
    }

    thead th {
        position: sticky;
        top: 0;
        background: var(--bg);
        border-bottom: 1px solid var(--ring);
        color: var(--muted);
        font-size: .75rem;
        letter-spacing: .5px;
        text-transform: uppercase;
        padding: 1rem;
    }

    tbody td {
        vertical-align: middle;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid var(--ring);
    }

    tbody tr:hover {
        background: #f1f5f9;
    }

    /* === Komponen Spesifik Tabel === */
    .status-badge {
        padding: .4rem .8rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: .8rem;
        white-space: nowrap;
    }

    .status-in {
        background: #dcfce7;
        color: #166534;
    }

    .thumbnail-img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: var(--radius-sm);
        cursor: pointer;
        border: 1px solid var(--ring);
        transition: transform .2s ease;
    }

    .thumbnail-img:hover {
        transform: scale(1.05);
    }

    .actions .btn-icon {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-md);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .2s ease;
    }

    .btn-ghost-danger {
        background: rgba(239, 68, 68, .1);
        color: var(--danger);
    }

    .btn-ghost-danger:hover {
        background: rgba(239, 68, 68, .2);
        transform: translateY(-2px);
    }

    /* === Empty State === */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--muted);
    }

    .empty-state i {
        width: 64px;
        height: 64px;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }

    /* === Modals === */
    .modal-content {
        border-radius: var(--radius-lg);
        border: none;
        box-shadow: var(--shadow-md);
    }
</style>
@endpush

@section('content')
<div class="financial-wrap">
    {{-- Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Pemasukan</h1>
            <p class="subtext mb-0">Kelola seluruh pemasukan kost</p>
        </div>
        <button class="btn btn-primary-ghost" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
            <i data-feather="plus-circle"></i> Tambah Pemasukan
        </button>
    </div>

    {{-- Summary chips --}}
    <div class="stat-grid">
        <div class="stat-card">
            <p class="stat-title">Total Pemasukan</p>
            <p class="stat-value">Rp {{ number_format($totalIncome,0,',','.') }}</p>
            <div class="stat-foot up">
                <i data-feather="trending-up"></i> Pendapatan bulan ini
            </div>
        </div>
        <div class="stat-card">
            <p class="stat-title">Jumlah Transaksi</p>
            <p class="stat-value">{{ $transactions->count() }}</p>
            <div class="stat-foot muted">
                <i data-feather="list"></i> Total baris data
            </div>
        </div>
        <div class="stat-card">
            <p class="stat-title">Rata-rata Per Transaksi</p>
            <p class="stat-value">
                Rp {{ number_format($transactions->count() ? floor($totalIncome / max(1,$transactions->count())) : 0,0,',','.') }}
            </p>
            <div class="stat-foot muted">
                <i data-feather="dollar-sign"></i> Estimasi
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card-base">
        <div class="table-toolbar">
            <div class="filter-inline">
                <input id="quickSearch" type="text" class="form-control" placeholder="Cari nama/kamar…">
            </div>
            <div class="text-muted small">
                Menampilkan {{ $transactions->count() }} transaksi
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="min-width:120px">No Kamar</th>
                        <th>Nama Transaksi</th>
                        <th style="width:140px">Tanggal</th>
                        <th style="width:160px">Total</th>
                        <th style="width:120px">Status</th>
                        <th style="width:90px">Bukti</th>
                        <th style="width:100px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($transactions as $t)
                        <tr data-id="{{ $t->id }}">
                            <td><span class="fw-semibold">Kamar {{ $t->kost->nomor_kamar ?? '-' }}</span></td>
                            <td>{{ $t->nama_transaksi }}</td>
                            <td>{{ optional($t->tanggal_transaksi)->format('d/m/Y') }}</td>
                            <td class="fw-semibold">Rp {{ number_format($t->total,0,',','.') }}</td>
                            <td><span class="status-badge status-in">{{ $t->status }}</span></td>
                            <td class="text-center">
                                @if($t->bukti_pembayaran)
                                    <img src="{{ $t->bukti_pembayaran_url }}"
                                         alt="Bukti Pembayaran"
                                         class="thumbnail-img"
                                         onclick="showImage('{{ $t->bukti_pembayaran_url }}')"
                                         onerror="this.onerror=null;this.src='/images/placeholder.jpg';">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center actions">
                                <button class="btn btn-ghost-danger btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Hapus" data-transaction-id="{{ $t->id }}">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i data-feather="inbox"></i>
                                    <div class="text-muted">Belum ada data pemasukan</div>
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
    document.addEventListener('DOMContentLoaded', () => {
        feather.replace();

        // Inisialisasi Tooltip
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        // Quick Search
        const quickSearchInput = document.getElementById('quickSearch');
        if (quickSearchInput) {
            quickSearchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('#tableBody tr').forEach(tr => {
                    const rowText = tr.innerText.toLowerCase();
                    tr.style.display = rowText.includes(q) ? '' : 'none';
                });
            });
        }

        // Image Preview Modal Logic
        window.showImage = (url) => {
            const img = document.getElementById('previewImage');
            img.src = url;
            new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
        };

        // Delete Transaction with Event Delegation
        document.getElementById('tableBody')?.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-ghost-danger');
            if (deleteBtn) {
                const transactionId = deleteBtn.getAttribute('data-transaction-id');
                Swal.fire({
                    title: 'Hapus Transaksi?',
                    text: 'Data yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(res => {
                    if (res.isConfirmed) {
                        fetch(`/admin/financial/${transactionId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(errorData => {
                                    throw new Error(errorData.message || 'Gagal menghapus');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire('Berhasil', 'Transaksi berhasil dihapus', 'success').then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire('Error', error.message, 'error');
                        });
                    }
                });
            }
        });
    });

    // Preview image on file input change
    window.previewSlip = (input) => {
        const box = document.getElementById('slipPreview');
        const img = box.querySelector('img');
        if (input.files && input.files[0]) {
            img.src = URL.createObjectURL(input.files[0]);
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
            img.src = '';
        }
    };
</script>
@endpush
