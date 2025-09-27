{{-- resources/views/user/history/index.blade.php (Improved) --}}
@extends('layouts.user')

@section('title', 'Riwayat Transaksi')

@push('css')
<style>
    /* === Palet & Umum (diselaraskan dengan desain lainnya) === */
    :root {
        --primary: #1a7f5a;
        --primary-2: #16c79a;
        --secondary: #f1f5f9;
        --surface: #ffffff;
        --bg: #f8fafc;
        --ink: #1e293b;
        --muted: #64748b;
        --ring: #e2e8f0;
        --success: #16a34a;
        --danger: #dc2626;
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
    .history-container {
        padding: 2rem;
        background: var(--bg);
        min-height: 100vh;
    }

    /* Header yang diperbarui */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        color: #fff;
        box-shadow: 0 4px 20px rgba(26, 127, 90, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
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

    /* === Main Content Card & Table === */
    .card-base {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        padding: 1.5rem;
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

    .fw-bold-dark {
        font-weight: 600;
        color: var(--ink);
    }

    .text-muted-sub {
        font-size: 0.875rem;
        color: var(--muted);
    }

    /* === Status Badges & Action Buttons === */
    .badge-status {
        padding: 0.4rem 0.8rem;
        border-radius: var(--radius-sm);
        font-weight: 500;
        font-size: 0.875rem;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .status-pemasukan { background: #dcfce7; color: var(--success); }
    .status-pengeluaran { background: #fee2e2; color: var(--danger); }

    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-md);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-action.view { background: rgba(59,130,246,.12); color: #1d4ed8; }
    .btn-action.view:hover { background: rgba(59,130,246,.18); transform: translateY(-2px); }

    .btn-action i { width: 18px; height: 18px; }

    /* === Modal === */
    .modal-content { border-radius: var(--radius-lg); border: none; }
    .modal-header { border-bottom: 1px solid var(--ring); padding: 1.5rem; background: var(--bg); }
    .modal-body { padding: 2rem; }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--muted);
    }
    .empty-state-icon {
        width: 4rem;
        height: 4rem;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .history-container { padding: 1rem; }
        .page-header {
            padding: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="history-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Riwayat Transaksi</h1>
            <p class="page-subtitle">Daftar semua transaksi pemasukan dan pengeluaran Anda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-base">
        <div class="table-responsive">
            <table class="table table-hover" id="historyTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>No Kamar</th>
                        <th>Nama Transaksi</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th class="text-center">Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $transaction->kost->nomor_kamar ?? '-' }}</td>
                        <td class="fw-bold-dark">{{ $transaction->nama_transaksi }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge-status status-{{ Str::slug($transaction->jenis) }}">
                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($transaction->bukti_pembayaran)
                                @php $img = asset('storage/' . $transaction->bukti_pembayaran); @endphp
                                <button type="button" class="btn-action view" onclick="showImage(`{{ $img }}`)">
                                    <i data-feather="eye"></i>
                                </button>
                            @else
                                <span class="text-muted-sub">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i data-feather="inbox" class="empty-state-icon"></i>
                                <p class="empty-state-text">Belum ada data transaksi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <img id="previewImage" src="" alt="Bukti Pembayaran" class="img-fluid rounded-bottom">
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function initIcons(){
        if(window.feather){ feather.replace({ 'stroke-width':1.5, width:20, height:20 }); }
    }

    function showImage(url) {
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        document.getElementById('previewImage').src = url;
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        initIcons();
    });
</script>
@endpush
