{{-- filepath: resources/views/admin/account/index.blade.php --}}
@extends('layouts.main')

@push('css')
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
    <style>
        .account-container {
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

        .account-card {
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
            vertical-align: middle;
            border-bottom: 1px solid #e9eef3;
            white-space: nowrap;
        }

        .table tr:hover {
            background: #f1f5f9;
        }

        .badge {
            padding: 6px 16px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .badge.bg-info {
            background: #e0f2fe !important;
            color: #0369a1 !important;
        }

        .badge.bg-success {
            background: #dcfce7 !important;
            color: #166534 !important;
        }

        .badge.bg-secondary {
            background: #f1f5f9 !important;
            color: #475569 !important;
        }

        /* Action Buttons */
        .action-btn {
            width: 32px;
            height: 32px;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 8px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .action-btn i {
            width: 16px !important;
            height: 16px !important;
            stroke-width: 2;
        }

        .btn-edit {
            background-color: #fef3c7 !important;
            color: #92400e !important;
        }

        .btn-delete {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
        }

        .btn-edit:hover,
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .modal-content {
            border: none;
            border-radius: 16px;
        }

        .modal .btn {
            padding: 8px 24px;
            border-radius: 8px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .modal .btn:hover {
            transform: translateY(-1px);
        }

        .modal .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
            border: none;
        }

        .modal .btn-secondary:hover {
            background: #e2e8f0;
            color: #475569;
        }

        .modal .btn-danger {
            background: #fee2e2;
            color: #991b1b;
            border: none;
        }

        .modal .btn-danger:hover {
            background: #fecaca;
            box-shadow: 0 4px 12px rgba(153,27,27,0.1);
        }
    </style>
@endpush

@section('title', 'Manajemen Akun')

@section('content')
<div class="account-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0">Manajemen Akun</h1>
        <a href="{{ route('admin.account.create') }}" class="btn btn-add">
            <i data-feather="plus-circle" class="me-2"></i>
            Tambah Akun
        </a>
    </div>

    <div class="account-card">
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
                                   class="btn btn-edit action-btn"
                                   title="Edit">
                                    <i data-feather="edit-2"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-delete action-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $user->id }}"
                                        title="Hapus">
                                    <i data-feather="trash-2"></i>
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
        feather.replace({
            'stroke-width': 2,
            'width': 16,
            'height': 16
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

        // Initialize icons on first load
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

