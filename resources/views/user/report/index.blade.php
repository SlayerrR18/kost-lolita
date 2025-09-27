{{-- resources/views/user/reports/index.blade.php (Improved) --}}
@extends('layouts.user')

@section('title', 'Riwayat Laporan')

@push('css')
<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
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
    .report-container {
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

    .page-header .header-content {
        flex-grow: 1;
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
    .content-card {
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

    .badge-status.dikirim { background: #fef3c7; color: #92400e; }
    .badge-status.sedang_dikerjakan { background: #dbeafe; color: #1e40af; }
    .badge-status.selesai { background: #dcfce7; color: #166534; }

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

    .btn-action.download { background: var(--secondary); color: var(--ink); }
    .btn-action.download:hover { background: #e2e8f0; transform: translateY(-1px); }

    .btn-action.delete { background: rgba(220, 38, 38, 0.1); color: var(--danger); }
    .btn-action.delete:hover { background: rgba(220, 38, 38, 0.2); transform: translateY(-2px); }

    .feather-16 { width: 1rem; height: 1rem; }

    .btn-add {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 12px rgba(26, 127, 90, 0.15);
        text-decoration: none;
    }
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(26, 127, 90, 0.2);
        color: #fff;
    }

    /* === Modal === */
    .modal-content { border-radius: var(--radius-lg); border: none; }
    .modal-header { border-bottom: 1px solid var(--ring); padding: 1.5rem; background: var(--bg); }
    .modal-body { padding: 2.5rem; }
    .modal-footer { border-top: 1px solid var(--ring); padding: 1.5rem; }

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
        .report-container { padding: 1rem; }
        .page-header {
            padding: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }
        .btn-add {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="report-container">
    <div class="page-header">
        <div class="header-content">
            <div>
                <h1 class="page-title">Riwayat Laporan</h1>
                <p class="page-subtitle">Daftar laporan Anda beserta tindak lanjut dari admin</p>
            </div>
            <a href="{{ route('user.reports.create') }}" class="btn btn-add">
                <i data-feather="file-plus"></i>
                <span>Buat Laporan</span>
            </a>
        </div>
    </div>

    <div class="content-card">
        <div class="table-container">
            @include('layouts.alert')
            <div class="table-responsive">
                <table class="table table-hover" id="reportTable" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Isi Laporan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Jawaban Penanggung Jawab</th>
                            <th>Penanggung Jawaban</th>
                            <th class="text-center" style="width:110px;">Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        @php
                            $tanggal = \Carbon\Carbon::parse($report->date ?? $report->created_at)->format('d/m/Y');
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-wrap" style="max-width:420px">{{ $report->message }}</td>
                            <td>{{ $tanggal }}</td>
                            <td>
                                <span class="badge-status {{ $report->status }}">
                                    @switch($report->status)
                                        @case('dikirim')
                                            <i data-feather="upload"></i> Dikirim
                                            @break
                                        @case('sedang_dikerjakan')
                                            <i data-feather="tool"></i> Sedang Dikerjakan
                                            @break
                                        @case('selesai')
                                            <i data-feather="check-circle"></i> Selesai
                                            @break
                                        @default
                                            <i data-feather="help-circle"></i> {{ \Illuminate\Support\Str::headline($report->status) }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="text-wrap" style="max-width:420px">
                                {{ $report->response ?? '-' }}
                            </td>
                            <td><span class="text-muted-sub">Owner</span></td>
                            {{-- <td><span class="text-muted-sub">{{ $report->handler->name ?? 'Owner' }}</span></td> --}}
                            <td class="text-center">
                                @if ($report->photo)
                                    @php $imgUrl = asset('storage/'.$report->photo); @endphp
                                    <div class="d-inline-flex gap-1">
                                        <button type="button"
                                                class="btn-action view"
                                                onclick="showImage(`{{ $imgUrl }}`)"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Lihat foto">
                                            <i data-feather="eye"></i>
                                        </button>
                                        <a href="{{ $imgUrl }}" download class="btn-action download"
                                            data-bs-toggle="tooltip" data-bs-title="Unduh">
                                            <i data-feather="download"></i>
                                        </a>
                                    </div>
                                @else
                                    <span class="badge-status muted">Tidak ada Foto</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i data-feather="inbox" class="empty-state-icon"></i>
                                    <p class="empty-state-text">Belum ada laporan yang dikirim.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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

        $('#reportTable').DataTable({
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
            }
        });
    });
</script>
@endpush
