{{-- filepath: resources/views/admin/account/index.blade.php --}}
@extends('layouts.main')

@push('css')
<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
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
        --radius-pill: 9999px; /* Untuk bentuk pill/chip */
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    /* === Layout & Containers === */
    .account-container {
        padding: 2rem;
        background: var(--bg);
        min-height: 100vh;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 4px 20px rgba(26, 127, 90, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: flex-start; /* Align items to top to accommodate filters */
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

    /* === Filter Section (NEW/IMPROVED) === */
    .filter-section {
        display: flex;
        gap: 0.75rem; /* Reduced gap for more compact look */
        flex-wrap: wrap;
        align-items: center;
        background: rgba(255, 255, 255, 0.15); /* Slightly transparent background */
        border-radius: var(--radius-pill); /* Pill shape */
        padding: 0.5rem 1.25rem; /* Padding for the entire filter section */
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .filter-input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .filter-input-group .form-control,
    .filter-input-group .form-select {
        border: none; /* Remove individual borders */
        border-radius: var(--radius-pill); /* Pill shape for inputs */
        padding: 0.5rem 1rem;
        padding-left: 2.25rem; /* Space for icon */
        font-size: 0.875rem; /* Smaller font size */
        background: rgba(255, 255, 255, 0.9); /* Opaque white background for inputs */
        color: var(--ink);
        transition: all 0.2s ease;
        height: 38px; /* Fixed height for consistency */
        min-width: 140px; /* Min-width for inputs */
        max-width: 180px; /* Max-width for inputs */
    }

    .filter-input-group .form-control::placeholder {
        color: var(--muted);
        opacity: 0.8;
    }

    .filter-input-group .form-control:focus,
    .filter-input-group .form-select:focus {
        box-shadow: 0 0 0 2px var(--primary-2); /* Focus ring with primary color */
        outline: none;
        background: var(--surface);
    }

    .filter-input-group .input-icon {
        position: absolute;
        left: 0.8rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1rem; /* Smaller icon size */
        height: 1rem;
        color: var(--muted);
        pointer-events: none;
        z-index: 2; /* Ensure icon is above input */
    }

    /* Specific style for select arrow */
    .filter-input-group .form-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-down'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.8rem center;
        background-size: 1rem;
        padding-right: 2.25rem; /* Space for custom arrow */
    }

    .btn-filter {
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
        height: 38px; /* Match input height */
        box-shadow: var(--shadow-md);
    }

    .btn-filter:hover {
        background: var(--secondary);
        color: var(--primary);
        transform: translateY(-1px);
        box-shadow: var(--shadow-lg);
    }

    /* === Stats Cards === */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
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
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        color: var(--ink);
        font-size: 1.5rem;
        font-weight: 700;
    }

    /* === Main Content Card === */
    .content-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* === DataTable Customization === */
    .dataTables_wrapper {
        padding: 1.5rem;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
        display: none; /* Hide default DataTables search/length */
    }

    /* Remove default DataTables filter styling */
    .dataTables_wrapper .dataTables_filter label {
        display: none;
    }
    .dataTables_wrapper .dataTables_filter input {
        display: none;
    }


    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 0.875rem;
        color: var(--muted);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: var(--radius-sm);
        margin: 0 4px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: rgba(26,127,90,.1);
        color: var(--primary) !important;
        border-color: transparent !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: rgba(26,127,90,.05) !important;
        border-color: transparent !important;
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

    .badge-status.active { background: #dcfce7; color: #166534; }
    .badge-status.inactive { background: #f1f5f9; color: #475569; }
    .badge-status.bg-info { background: #e0f2fe; color: #075985; }
    .badge-status.bg-secondary { background: #e2e8f0; color: #475569; }

    /* === Action Buttons & Icons === */
    .btn-add {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 12px rgba(26, 127, 90, 0.15);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(26, 127, 90, 0.2);
        color: white;
    }

    .action-btn {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-md);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
    }

    .btn-edit { background: rgba(26, 127, 90, 0.1); color: var(--primary); }
    .btn-edit:hover { background: rgba(26, 127, 90, 0.2); transform: translateY(-2px); }

    .btn-delete { background: rgba(220, 38, 38, 0.1); color: var(--danger); }
    .btn-delete:hover { background: rgba(220, 38, 38, 0.2); transform: translateY(-2px); }

    .feather-16 { width: 1rem; height: 1rem; }

    /* === Modal Styles === */
    .modal-content { border-radius: var(--radius-lg); border: none; }
    .modal-header { border-bottom: none; padding: 1.5rem 1.5rem 0; }
    .modal-footer { border-top: none; padding: 0 1.5rem 1.5rem; }

    .modal-body { padding: 2.5rem; text-align: center; }
    .modal-icon { width: 4rem; height: 4rem; margin-bottom: 1.5rem; }

    /* Responsive Adjustments */
    @media (max-width: 992px) { /* Adjust breakpoint for filter section */
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }
        .filter-section {
            width: 100%;
            justify-content: center;
            border-radius: var(--radius-lg); /* More rounded on smaller screens */
            padding: 1rem;
        }
        .filter-input-group .form-control,
        .filter-input-group .form-select {
            min-width: unset; /* Remove min-width on small screens */
            width: 100%; /* Make them full width */
            max-width: 100%;
        }
        .filter-input-group {
            width: 100%; /* Make input groups full width */
        }
    }

    @media (max-width: 768px) {
        .account-container { padding: 1rem; }
        .page-header { padding: 1.5rem; }
        .stats-grid { grid-template-columns: 1fr; }
        .header-content { flex-direction: column; align-items: flex-start; }
        .btn-add { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('title', 'Manajemen Akun')

@section('content')
<div class="account-container">
    {{-- Header Section --}}
    <header class="page-header">
        <div class="header-content">
            <h1 class="page-title">Manajemen Akun</h1>
            <p class="page-subtitle">Kelola data penghuni kost dengan mudah</p>
        </div>

        {{-- Filter Section --}}
        <div class="filter-section">
            <div class="filter-input-group">
                <i data-feather="search" class="input-icon"></i>
                <input type="text" id="quickSearch" class="form-control" placeholder="Cari Nama/Email...">
            </div>

            <div class="filter-input-group">
                <i data-feather="users" class="input-icon"></i>
                <select id="filterStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Penghuni">Penghuni</option>
                    <option value="Bukan Penghuni">Bukan Penghuni</option>
                </select>
            </div>

            <div class="filter-input-group">
                <i data-feather="home" class="input-icon"></i>
                <input type="text" id="filterRoom" class="form-control" placeholder="No. Kamar">
            </div>

            {{-- Tombol "Tambah Penghuni" dipindahkan di sini untuk konsistensi --}}
            <a href="{{ route('admin.account.create') }}" class="btn btn-add">
                <i data-feather="user-plus" class="feather-16"></i>
                <span>Tambah</span>
            </a>
        </div>
    </header>

    {{-- Stats Grid --}}
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Akun Terdaftar</div>
            <div class="stat-value">{{ $users->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Penghuni Aktif</div>
            <div class="stat-value">{{ $users->where('kost.status', 'Terisi')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Akun Tanpa Kamar</div>
            <div class="stat-value">{{ $users->where('kost', null)->count() }}</div>
        </div>
    </section>

    {{-- Main Content Card --}}
    <div class="content-card">
        @include('layouts.alert')
        {{-- `table-toolbar` sudah tidak diperlukan karena filter sudah di header --}}
        {{-- <div class="table-toolbar">
            <div class="filter-inline">
                <select id="filterStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Penghuni">Penghuni</option>
                    <option value="Bukan Penghuni">Bukan Penghuni</option>
                </select>
                <input type="text" id="filterRoom" class="form-control" placeholder="Cari No. Kamar">
                <input type="text" id="quickSearch" class="form-control" placeholder="Cari Nama/Email...">
            </div>
        </div> --}}
        <div class="table-responsive">
            <table class="table table-hover" id="accountTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Penghuni</th>
                        <th>Email</th>
                        <th>Kamar</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Keluar</th>
                        <th>Status</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td></td>
                        <td class="fw-bold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->kost)
                                <span class="badge badge-status bg-info">{{ $user->kost->nomor_kamar }}</span>
                            @else
                                <span class="badge badge-status bg-secondary">Tidak Ada Kamar</span>
                            @endif
                        </td>
                        <td>
                            {{ optional($user->orders->first())->tanggal_masuk?->format('d M Y') ?? '-' }}
                        </td>
                        <td>
                            {{ optional($user->orders->first())->tanggal_keluar?->format('d M Y') ?? '-' }}
                        </td>
                        <td>
                            @if($user->kost && $user->kost->penghuni)
                                <span class="badge badge-status active">Penghuni</span>
                            @else
                                <span class="badge badge-status inactive">Bukan Penghuni</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.account.edit', $user->id) }}"
                                   class="action-btn btn-edit" title="Edit">
                                    <i data-feather="edit-2"></i>
                                </a>
                                <button type="button" class="action-btn btn-delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $user->id }}"
                                        title="Hapus">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Delete Modal --}}
                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body text-center p-5">
                                    <div class="text-danger mb-4">
                                        <i data-feather="alert-circle" class="modal-icon"></i>
                                    </div>
                                    <h4 class="text-danger mb-3">Hapus Akun</h4>
                                    <p class="mb-4 text-muted">Apakah Anda yakin ingin menghapus akun {{ $user->name }}? Data yang sudah dihapus tidak dapat dikembalikan.</p>
                                    <form action="{{ route('admin.account.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="d-flex justify-content-center gap-3">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });

    $(document).ready(function() {
        // --- Inisialisasi DataTable ---
        var table = $('#accountTable').DataTable({
            "columnDefs": [
                { "searchable": false, "orderable": false, "targets": 0 }
            ],
            "pageLength": 10,
            "order": [],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data yang ditampilkan",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "zeroRecords": "Tidak ada data yang cocok",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            },
            "drawCallback": function() {
                feather.replace();
            },
            "initComplete": function() {
                feather.replace();
            }
        });

        // --- Penomoran baris ---
        table.on('order.dt search.dt', function () {
            let i = 1;
            table.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell) {
                cell.innerHTML = i++;
            });
            feather.replace();
        }).draw();

        // --- Filter Kustom ---
        // Gunakan fungsi search() DataTables yang sudah ada
        $('#quickSearch').on('keyup', function() {
            table.search(this.value).draw();
            feather.replace();
        });

        $('#filterStatus').on('change', function() {
            let statusValue = $(this).val();
            // Kolom Status ada di index 6
            if (statusValue) {
                table.column(6).search(statusValue, true, false).draw();
            } else {
                table.column(6).search('').draw();
            }
            feather.replace();
        });

        $('#filterRoom').on('keyup', function() {
            // Kolom Kamar ada di index 3
            table.column(3).search(this.value).draw();
            feather.replace();
        });

        // Fix Bootstrap modal conflict
        $.fn.modal.Constructor.Default.keyboard = false;
        $.fn.modal.Constructor.Default.backdrop = 'static';
    });
</script>
@endpush
