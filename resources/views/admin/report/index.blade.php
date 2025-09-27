{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.main')

@section('title','Report Penghuni')

@push('css')
<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
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
    .report-container {
        padding: 2rem;
        background: var(--bg);
        min-height: 100vh;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        color: #fff;
        box-shadow: 0 4px 20px rgba(26, 127, 90, .15);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .header-content {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
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

    /* === Filter Section (IMPROVED) === */
    .filter-section {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
        background: rgba(255, 255, 255, 0.15);
        border-radius: var(--radius-pill);
        padding: 0.5rem 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .filter-input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .filter-input-group .form-control,
    .filter-input-group .form-select {
        border: none;
        border-radius: var(--radius-pill);
        padding: 0.5rem 1rem;
        padding-left: 2.25rem;
        font-size: 0.875rem;
        background: rgba(255, 255, 255, 0.9);
        color: var(--ink);
        transition: all 0.2s ease;
        height: 38px;
        min-width: 140px;
    }

    .filter-input-group .form-control[type="date"] {
        padding-right: 1rem;
    }

    .filter-input-group .form-control::placeholder {
        color: var(--muted);
        opacity: 0.8;
    }

    .filter-input-group .form-control:focus,
    .filter-input-group .form-select:focus {
        box-shadow: 0 0 0 2px var(--primary-2);
        outline: none;
        background: var(--surface);
    }

    .filter-input-group .input-icon {
        position: absolute;
        left: 0.8rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1rem;
        height: 1rem;
        color: var(--muted);
        pointer-events: none;
        z-index: 2;
    }

    .filter-input-group .form-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-down'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.8rem center;
        background-size: 1rem;
        padding-right: 2.25rem;
    }

    .btn-add-filter {
        background: white;
        color: var(--primary);
        border: none;
        border-radius: var(--radius-pill);
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        height: 38px;
        box-shadow: var(--shadow-md);
    }

    .btn-add-filter:hover {
        background: var(--secondary);
        color: var(--primary);
        transform: translateY(-1px);
        box-shadow: var(--shadow-lg);
    }

    /* === Main Content Card === */
    .content-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-container {
        padding: 1.5rem;
    }

    .dataTables_wrapper {
        padding: 0;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        display: none;
    }

    /* === Tabel Styling === */
    .table-responsive {
        padding: 1rem;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: var(--bg);
        border-bottom: 1px solid var(--ring);
        color: var(--muted);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table tbody td {
        vertical-align: middle;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid var(--ring);
    }

    .table tbody tr:hover {
        background-color: #f1f5f9;
    }

    .text-muted-sub {
        font-size: 0.875rem;
        color: var(--muted);
    }

    /* === Status Badges === */
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

    .badge-status.dikirim { background: #fef3c7; color: #92400e; }
    .badge-status.sedang_dikerjakan { background: #dbeafe; color: #1e40af; }
    .badge-status.selesai { background: #dcfce7; color: #166534; }

    /* === Action Buttons & Icons === */
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

    .btn-detail { background: rgba(59,130,246,.12); color: #1d4ed8; }
    .btn-detail:hover { background: rgba(59,130,246,.18); transform: translateY(-2px); }

    .btn-download { background: var(--secondary); color: var(--ink); }
    .btn-download:hover { background: #e2e8f0; transform: translateY(-1px); }

    .btn-delete { background: rgba(220, 38, 38, 0.1); color: var(--danger); }
    .btn-delete:hover { background: rgba(220, 38, 38, 0.2); transform: translateY(-2px); }

    .feather-16 { width: 1rem; height: 1rem; }

    /* === Modal Styles === */
    .modal-content { border-radius: var(--radius-lg); border: none; }
    .modal-header { border-bottom: 1px solid var(--ring); padding: 1.5rem; background: var(--bg); }
    .modal-footer { border-top: 1px solid var(--ring); padding: 1.5rem; }

    .modal-body { padding: 2.5rem; text-align: center; }
    .modal-icon { width: 4rem; height: 4rem; margin-bottom: 1.5rem; }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }
        .filter-section {
            width: 100%;
            justify-content: center;
            border-radius: var(--radius-lg);
            padding: 1rem;
        }
        .filter-input-group .form-control,
        .filter-input-group .form-select {
            min-width: unset;
            width: 100%;
            max-width: 100%;
        }
        .filter-input-group {
            width: 100%;
        }
    }
    @media (max-width: 768px) {
        .report-container { padding: 1rem; }
        .page-header { padding: 1.5rem; }
    }
</style>
@endpush

@section('title','Report Penghuni')

@section('content')
<div class="report-container">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Report Penghuni</h1>
            <p class="page-subtitle">Daftar semua masukan dan keluhan dari penghuni.</p>
        </div>

        <form action="{{ route('admin.reports.index') }}" method="get" class="filter-section">
            @csrf
            <div class="filter-input-group">
                <i data-feather="search" class="input-icon"></i>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama/isi">
            </div>

            <div class="filter-input-group">
                <i data-feather="tool" class="input-icon"></i>
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach([
                        'dikirim' => 'Dikirim',
                        'sedang_dikerjakan' => 'Sedang Dikerjakan',
                        'selesai' => 'Selesai'
                    ] as $k=>$v)
                        <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-input-group">
                <i data-feather="calendar" class="input-icon"></i>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
            </div>

            <div class="filter-input-group">
                <i data-feather="calendar" class="input-icon"></i>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
            </div>

            <button type="submit" class="btn btn-add-filter">
                <i data-feather="filter" class="feather-16"></i> Terapkan
            </button>
        </form>
    </div>

    <div class="content-card">
        <div class="table-container">
            @include('layouts.alert')
            <div class="table-responsive">
                <table class="table align-middle" id="reportTable">
                    <thead>
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Nama Penghuni</th>
                            <th>Isi</th>
                            <th>Tanggal</th>
                            <th style="width:110px;">Foto</th>
                            <th>Status</th>
                            <th>Penanggung Jawab</th>
                            <th style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $r)
                        <tr data-report-id="{{ $r->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $r->user->name ?? '-' }}</td>
                            <td><span class="text-muted-sub">{{ Str::limit($r->message, 120) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($r->date ?? $r->created_at)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @if($r->photo)
                                    @php $img = asset('storage/'.$r->photo); @endphp
                                    <div class="d-inline-flex gap-1">
                                        <button type="button"
                                                class="btn-action btn-detail"
                                                data-bs-toggle="modal"
                                                data-bs-target="#imageModal"
                                                data-img-url="{{ $img }}"
                                                title="Lihat foto"
                                                aria-label="Lihat foto">
                                            <i data-feather="eye"></i>
                                        </button>
                                        <a href="{{ $img }}"
                                           class="btn-action btn-download"
                                           download
                                           title="Unduh"
                                           aria-label="Unduh foto">
                                            <i data-feather="download"></i>
                                        </a>
                                    </div>
                                @else
                                    <span class="badge">Tidak Ada Foto</span>
                                @endif
                            </td>
                            <td><span class="badge-status {{ $r->status }}">{{ Str::headline($r->status) }}</span></td>
                            <td><span class="text-muted-sub">Owner</span></td>
                            <td>
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.reports.show', $r) }}"
                                       class="btn-action btn-detail"
                                       title="Detail"
                                       aria-label="Detail">
                                        <i data-feather="info"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-report-id="{{ $r->id }}"
                                            title="Hapus report">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($reports->lastPage() > 1)
            <div class="mt-3">
                {{ $reports->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Lihat Foto Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <img id="previewImage" src="" alt="Foto Laporan" class="img-fluid rounded-bottom">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i data-feather="alert-triangle" class="modal-icon text-danger mb-4" aria-hidden="true"></i>
                <h5 class="mb-3">Hapus Laporan Ini?</h5>
                <p class="text-muted mb-0">Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    function initIcons(){
        if(window.feather){ feather.replace({ 'stroke-width':1.5, width:16, height:16 }); }
    }
    function initTooltips(){
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    }

    $(function(){
        // DataTable Initialization
        var t = $('#reportTable').DataTable({
            columnDefs:[{searchable:false,orderable:false,targets:0}],
            pageLength:10, order:[],
            language:{
                search:"Cari:", lengthMenu:"Tampilkan _MENU_ data",
                info:"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty:"Tidak ada data", infoFiltered:"(difilter dari _MAX_ total data)",
                zeroRecords:"Tidak ada data yang cocok",
                paginate:{first:"Pertama",last:"Terakhir",next:"Selanjutnya",previous:"Sebelumnya"}
            },
            drawCallback:function(){ initIcons(); initTooltips(); },
            initComplete:function(){ initIcons(); initTooltips(); }
        });

        // Remove original datatables search/length
        $('.dataTables_filter, .dataTables_length').hide();

        // Event listener untuk tombol hapus
        $('#reportTable').on('click', '.btn-delete', function() {
            const reportId = $(this).data('report-id');
            const formAction = '{{ route("admin.reports.destroy", ":id") }}'.replace(':id', reportId);
            $('#deleteForm').attr('action', formAction);
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
            feather.replace(); // Re-render ikon di modal
        });

        // Event listener untuk tombol lihat foto
        $('#reportTable').on('click', '.btn-detail', function() {
            const imgUrl = $(this).data('img-url');
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            document.getElementById('previewImage').src = imgUrl;
            imageModal.show();
            feather.replace();
        });

        // Re-render ikon saat modal dibuka
        $('#imageModal, #deleteModal').on('shown.bs.modal', function () {
            feather.replace();
        });
    });
</script>
@endpush
