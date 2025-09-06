{{-- resources/views/admin/financial/pengeluaran.blade.php --}}
@extends('layouts.main')

@section('title', 'Pengeluaran')

@push('css')
<style>
    /* === Palet & Umum (diselaraskan dengan desain sebelumnya) === */
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

    .btn-add {
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

    .btn-add:hover {
        background: rgba(255, 255, 255, .25);
        color: #fff;
        transform: translateY(-1px);
    }

    @media (max-width: 576px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .btn-add {
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
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
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
    .stat-foot.down { color: var(--danger); }
    .stat-foot.up { color: var(--success); }
    .stat-foot.muted { color: var(--muted); }


    /* === Main Content Card & Table === */
    .content-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-toolbar {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 1.5rem 0;
    }

    .filter-inline {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
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
        overflow-y: auto;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        position: sticky;
        top: 0;
        background: var(--bg);
        border-bottom: 1px solid var(--ring);
        color: var(--muted);
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 1rem;
    }

    .table tbody td {
        vertical-align: middle;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid var(--ring);
    }

    .table tbody tr:hover {
        background-color: #f1f5f9;
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

    .badge-status.status-out {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-status.status-in {
        background: #dcfce7;
        color: #166534;
    }

    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-md);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
    }

    .btn-delete {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger);
    }

    .btn-delete:hover {
        background: rgba(220, 38, 38, 0.2);
        transform: translateY(-2px);
    }

    .btn-preview-img {
        background: var(--secondary);
        color: var(--muted);
        border: 1px solid var(--ring);
    }
    .btn-preview-img:hover {
        background: #e2e8f0;
        transform: translateY(-1px);
    }

    .feather-16 {
        width: 1rem;
        height: 1rem;
    }

    /* === Modal === */
    .modal-content {
        border-radius: var(--radius-lg);
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid var(--ring);
        padding: 1.5rem;
        background-color: var(--bg);
    }
    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .modal-body {
        padding: 2rem;
        text-align: center;
    }

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
</style>
@endpush

@section('content')
<div class="financial-wrap">
    {{-- Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Pengeluaran</h1>
            <p class="subtext mb-0">Daftar dan ringkasan seluruh biaya operasional kost</p>
        </div>
        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
            <i data-feather="plus-circle"></i> Tambah Pengeluaran
        </button>
    </div>

    {{-- Summary chips --}}
    <div class="stat-grid">
        <div class="stat-card">
            <p class="stat-title">Total Pengeluaran</p>
            <p class="stat-value">Rp {{ number_format($totalExpense,0,',','.') }}</p>
            <div class="stat-foot down">
                <i data-feather="trending-down"></i> Biaya bulan ini
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
                Rp {{ number_format($transactions->count() ? floor($totalExpense / max(1,$transactions->count())) : 0,0,',','.') }}
            </p>
            <div class="stat-foot muted">
                <i data-feather="dollar-sign"></i> Estimasi
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="content-card">
        <div class="table-toolbar">
            <div class="filter-inline">
                <input id="quickSearch" type="text" class="form-control" placeholder="Cari nama/kamar…">
            </div>
            <div class="text-muted small">
                Menampilkan <span id="transactionCount">{{ $transactions->count() }}</span> transaksi
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="transactionTable">
                <thead>
                    <tr>
                        <th style="min-width:120px">No Kamar</th>
                        <th>Nama Transaksi</th>
                        <th style="width:140px">Tanggal</th>
                        <th style="width:160px">Total</th>
                        <th style="width:120px">Status</th>
                        <th style="width:90px" class="text-center">Bukti</th>
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
                        <td><span class="badge-status status-out">{{ $t->status }}</span></td>
                        <td class="text-center">
                            @if($t->bukti_pembayaran)
                                @php $imgUrl = asset('storage/' . $t->bukti_pembayaran); @endphp
                                <button type="button" class="btn-action btn-preview-img" onclick="showImage('{{ $imgUrl }}')">
                                    <i data-feather="eye"></i>
                                </button>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-center actions">
                            <button class="btn-action btn-delete" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Hapus" data-transaction-id="{{ $t->id }}">
                                <i data-feather="trash-2"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i data-feather="inbox" class="empty-state-icon"></i>
                                <h5>Belum ada data pengeluaran</h5>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal for Image Preview --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lihat Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" alt="Bukti Pembayaran" class="img-fluid rounded" />
            </div>
        </div>
    </div>
</div>

@include('admin.financial.partials.add-modal')
@endsection

@push('js')
<script>
    function initIcons(){
        if(window.feather){ feather.replace({ 'stroke-width':1.5, width:20, height:20 }); }
    }

    function initTooltips(){
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    }

    function showImage(url) {
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        document.getElementById('previewImage').src = url;
        modal.show();
    }

    function deleteTransaction(id){
        Swal.fire({
            title:'Hapus Transaksi?', text:'Data yang dihapus tidak dapat dikembalikan.',
            icon:'warning', showCancelButton:true, confirmButtonColor:'#dc2626', cancelButtonColor:'#6b7280',
            confirmButtonText:'Ya, hapus', cancelButtonText:'Batal'
        }).then(res=>{
            if(!res.isConfirmed) return;
            fetch(`/admin/financial/${id}`,{
                method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
            }).then(r=>r.json()).then(d=>{
                if(d.success){ Swal.fire('Berhasil','Transaksi dihapus','success').then(()=>location.reload()); }
                else{ throw new Error(d.message || 'Gagal menghapus'); }
            }).catch(e=>Swal.fire('Error', e.message, 'error'));
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initIcons();
        initTooltips();

        // Custom search filter logic
        const quickSearchInput = document.getElementById('quickSearch');
        const tableBody = document.getElementById('tableBody');
        const transactionCountSpan = document.getElementById('transactionCount');

        if (quickSearchInput && tableBody && transactionCountSpan) {
            quickSearchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase();
                let visibleCount = 0;

                tableBody.querySelectorAll('tr').forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    if (rowText.includes(query)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                transactionCountSpan.textContent = visibleCount;

                const emptyState = tableBody.querySelector('.empty-state');
                if (visibleCount === 0 && !emptyState) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.innerHTML = `<td colspan="7"><div class="empty-state"><i data-feather="inbox" class="empty-state-icon"></i><h5 class="mt-4">Tidak ada data yang cocok</h5></div></td>`;
                    tableBody.appendChild(emptyRow);
                    feather.replace();
                } else if (visibleCount > 0 && emptyState) {
                    emptyState.parentElement.parentElement.remove();
                }
            });
        }

        // Delete button event delegation
        document.getElementById('transactionTable')?.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-delete');
            if (deleteBtn) {
                const transactionId = deleteBtn.getAttribute('data-transaction-id');
                deleteTransaction(transactionId);
            }
        });
    });
</script>
@endpush
