{{-- filepath: resources/views/admin/account/index.blade.php --}}
@extends('layouts.main')

@push('css')
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
    <style>
        /* Modern Dashboard Container */
        .account-container {
            padding: 2rem;
            background: #f8fafc;
            min-height: 100vh;
        }

        /* Header Section */
        .page-header {
            background: linear-gradient(135deg, #1a7f5a 0%, #16c79a 100%);
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 4px 20px rgba(26, 127, 90, 0.15);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .stat-title {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            color: #1e293b;
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* Main Content Card */
        .content-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* Enhanced Table Styles */
        .table-container {
            padding: 1.5rem;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .table-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
        }

        .btn-add {
            background: linear-gradient(135deg, #1a7f5a 0%, #16c79a 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
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

        /* DataTable Customization */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #1a7f5a;
            box-shadow: 0 0 0 3px rgba(26, 127, 90, 0.1);
            outline: none;
        }

        /* Enhanced Status Badges */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .badge.active {
            background: #dcfce7;
            color: #166534;
        }

        .badge.inactive {
            background: #f1f5f9;
            color: #475569;
        }

        /* Action Buttons */
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            border: none;
        }

        .btn-edit {
            background: rgba(26, 127, 90, 0.1);
            color: #1a7f5a;
        }

        .btn-edit:hover {
            background: rgba(26, 127, 90, 0.2);
            transform: translateY(-2px);
        }

        .btn-delete {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
        }

        .btn-delete:hover {
            background: rgba(220, 38, 38, 0.2);
            transform: translateY(-2px);
        }

        /* Enhanced Modal */
        .modal-content {
            border-radius: 24px;
            border: none;
        }

        .modal-body {
            padding: 2.5rem;
            text-align: center;
        }

        .modal-icon {
            width: 80px;
            height: 80px;
            color: #dc2626;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .modal-text {
            color: #64748b;
            margin-bottom: 2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .account-container {
                padding: 1rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('title', 'Manajemen Akun')

@section('content')
<div class="account-container">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div>
                <h1 class="page-title">Penghuni Kost</h1>
                <p class="page-subtitle">Kelola data penghuni kost dengan mudah</p>
            </div>
            <a href="{{ route('admin.account.create') }}" class="btn btn-add">
                <i data-feather="user-plus"></i>
                <span>Tambah Penghuni</span>
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Penghuni</div>
            <div class="stat-value">{{ $users->where('kost', '!=', null)->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Kamar Terisi</div>
            <div class="stat-value">{{ $users->where('kost.status', 'Terisi')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Kamar Kosong</div>
            <div class="stat-value">{{ $users->where('kost.status', 'Kosong')->count() }}</div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-card">
        <div class="table-container">
            @include('layouts.alert')
            <div class="table-responsive">
                <table class="table" id="accountTable">
                    <thead>
                        <tr>
                            <th>No</th>
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
                                    <span class="badge bg-info">{{ $user->kost->nomor_kamar }}</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Ada Kamar</span>
                                @endif
                            </td>
                            <td>
                                @if($user->orders->where('status', 'confirmed')->first())
                                    {{ $user->orders->where('status', 'confirmed')->first()->tanggal_masuk->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($user->orders->where('status', 'confirmed')->first())
                                    {{ $user->orders->where('status', 'confirmed')->first()->tanggal_keluar->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($user->kost && $user->kost->penghuni)
                                    <span class="badge bg-success">Penghuni</span>
                                @else
                                    <span class="badge bg-secondary">Bukan Penghuni</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.account.edit', $user->id) }}"
                                       class="action-btn btn-edit"
                                       title="Edit">
                                        <i data-feather="edit-2" class="feather-16"></i>
                                    </a>
                                    <button type="button"
                                            class="action-btn btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $user->id }}"
                                            title="Hapus">
                                    <i data-feather="trash-2" class="feather-16"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body text-center p-5">
                                    <div class="text-danger mb-4">
                                        <i data-feather="alert-circle" style="width: 64px; height: 64px;"></i>
                                    </div>
                                    <h4 class="text-danger mb-3">Hapus Akun</h4>
                                    <p class="mb-4">Apakah Anda yakin ingin menghapus akun {{ $user->name }}? Data yang sudah dihapus tidak dapat dikembalikan.</p>
                                    <form action="{{ route('admin.account.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-secondary px-4 me-2" data-bs-dismiss="modal">
                                            <i data-feather="x" class="me-2"></i>
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-danger px-4">
                                            <i data-feather="trash-2" class="me-2"></i>
                                            Ya, Hapus
                                        </button>
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
<script>
    function initIcons() {
        // Clear existing icons first
        document.querySelectorAll('.feather').forEach(icon => icon.remove());

        // Reinitialize icons
        feather.replace({
            'stroke-width': 1.5,
            'width': 16,
            'height': 16,
            'class': 'feather-16'
        });
    }

    $(document).ready(function() {
        // Initialize DataTable
        var t = $('#accountTable').DataTable({
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
            }],
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
                initIcons();
            },
            "initComplete": function() {
                initIcons();
            }
        });

        // Row numbering
        t.on('order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i+1;
            });
            initIcons();
        });

        // Ensure icons are initialized after DataTable operations
        $('#accountTable').on('draw.dt', function() {
            setTimeout(initIcons, 50);
        });

        // Initial icon initialization
        initIcons();

        // Handle modal events
        $(document).on('shown.bs.modal', '.modal', function() {
            initIcons();
        });

        // Handle page changes
        $('#accountTable').on('page.dt', function() {
            setTimeout(initIcons, 100);
        });

        // Handle length change
        $('#accountTable').on('length.dt', function() {
            setTimeout(initIcons, 100);
        });

        // Handle search
        $('#accountTable').on('search.dt', function() {
            setTimeout(initIcons, 100);
        });
    });
</script>
@endpush

