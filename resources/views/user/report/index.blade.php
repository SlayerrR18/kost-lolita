@extends('layouts.user')

@push('css')
<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<style>
    .report-container{padding:2rem;background:#f8fafc;min-height:100vh}

    .page-header{background:linear-gradient(135deg,#1a7f5a 0%,#16c79a 100%);border-radius:24px;padding:2rem;margin-bottom:2rem;color:#fff;box-shadow:0 4px 20px rgba(26,127,90,.15)}
    .header-content{display:flex;justify-content:space-between;align-items:center}
    .page-title{font-size:1.75rem;font-weight:700;margin:0}
    .page-subtitle{opacity:.9;margin-top:.5rem}

    .content-card{background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);overflow:hidden}
    .table-container{padding:1.5rem}
    .table-title{font-size:1.25rem;font-weight:600;color:#1e293b}

    .btn-add{background:linear-gradient(135deg,#1a7f5a 0%,#16c79a 100%);color:#fff;padding:.75rem 1.5rem;border-radius:12px;font-weight:500;display:inline-flex;align-items:center;gap:.5rem;transition:all .3s ease;border:none;box-shadow:0 4px 12px rgba(26,127,90,.15)}
    .btn-add:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(26,127,90,.2);color:#fff}

    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input{border:2px solid #e2e8f0;border-radius:8px;padding:.5rem;transition:all .3s}
    .dataTables_wrapper .dataTables_filter input:focus{border-color:#1a7f5a;box-shadow:0 0 0 3px rgba(26,127,90,.1);outline:none}

    .badge{padding:.4rem .8rem;border-radius:8px;font-weight:500;font-size:.875rem}
    .badge.muted{background:#f1f5f9;color:#475569}
    .badge.info{background:#e0f2fe;color:#075985}
    .badge.open{background:#fef3c7;color:#92400e}
    .badge.in_progress{background:#dbeafe;color:#1e40af}
    .badge.resolved{background:#dcfce7;color:#166534}

    /* Update badge styles */
    .badge.dikirim {
        background: #fef3c7;
        color: #92400e;
    }
    .badge.sedang_dikerjakan {
        background: #dbeafe;
        color: #1e40af;
    }
    .badge.selesai {
        background: #dcfce7;
        color: #166534;
    }

    .btn-icon.view{background:rgba(26,127,90,.10);color:#1a7f5a}
    .btn-icon.view:hover{background:rgba(26,127,90,.18);transform:translateY(-1px)}
    .btn-icon.download{background:#eef2f7;color:#0f172a}
    .btn-icon.download:hover{background:#e3e9f0;transform:translateY(-1px)}


    .btn-icon{width:36px;height:36px;border-radius:10px;border:none;display:inline-flex;align-items:center;justify-content:center;background:#eef2f7}
    .btn-icon:hover{background:#e3e9f0}
    @media (max-width:768px){ .report-container{padding:1rem} .page-header{padding:1.5rem} }
</style>
@endpush

@section('title','Report')

@section('content')
<div class="report-container">
    {{-- Header --}}
    <div class="page-header">
        <div class="header-content">
            <div>
                <h1 class="page-title">Report</h1>
                <p class="page-subtitle">Daftar laporan Anda beserta tindak lanjut dari admin</p>
            </div>
            <a href="{{ route('user.reports.create') }}" class="btn btn-add">
                <i data-feather="file-plus"></i>
                <span>Buat Laporan</span>
            </a>
        </div>
    </div>

    {{-- Card + Table --}}
    <div class="content-card">
        <div class="table-container">
            @include('layouts.alert')

            <div class="table-responsive">
                <table class="table" id="reportTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Isi Laporan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Jawaban Penanggung Jawab</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        @php
                            $tanggal = \Carbon\Carbon::parse($report->date ?? $report->created_at)->format('d/m/Y');
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-wrap" style="max-width:420px">{{ $report->message }}</td>
                            <td>{{ $tanggal }}</td>
                            <td>
                                <span class="badge {{ $report->status }}">
                                    @switch($report->status)
                                        @case('dikirim')
                                            Dikirim
                                            @break
                                        @case('sedang_dikerjakan')
                                            Sedang Dikerjakan
                                            @break
                                        @case('selesai')
                                            Selesai
                                            @break
                                        @default
                                            {{ \Illuminate\Support\Str::headline($report->status) }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="text-wrap" style="max-width:420px">
                                {{ $report->response ?? '-' }}
                            </td>
                            <td class="text-center">
                            @if ($report->photo)
                                @php $imgUrl = asset('storage/'.$report->photo); @endphp

                                <button type="button"
                                        class="btn-icon view"
                                        onclick="showImage(`{{ $imgUrl }}`)"
                                        data-bs-toggle="tooltip"
                                        data-bs-title="Lihat foto">
                                    <i data-feather="eye"></i>
                                </button>

                                <a href="{{ $imgUrl }}" download class="btn-icon download ms-1"
                                data-bs-toggle="tooltip" data-bs-title="Unduh">
                                    <i data-feather="download"></i>
                                </a>
                            @else
                                <span class="badge muted">Tidak ada Foto</span>
                            @endif
                        </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function initIcons() {
        document.querySelectorAll('.feather').forEach(i => i.remove());
        if (window.feather) {
            feather.replace({ 'stroke-width': 1.5, width: 16, height: 16, class: 'feather-16' });
        }
    }

  function showImage(url) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'imageModal';
        modal.tabIndex = -1;
        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Lihat Foto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img id="previewImage" src="${url}" alt="Image" class="img-fluid" />
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        new bootstrap.Modal(modal).show();
    }

    $(document).ready(function () {
        var t = $('#reportTable').DataTable({
            columnDefs: [{ searchable: false, orderable: false, targets: 0 }],
            pageLength: 10,
            order: [],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak da data yang ditampilkana",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok",
                paginate: { first: "Pertama", last: "Terakhir", next: "Selanjutnya", previous: "Sebelumnya" }
            },
            drawCallback: initIcons,
            initComplete: initIcons
        });

        // Nomor urut otomatis
        t.on('order.dt search.dt', function () {
            t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
            initIcons();
        });

        // Re-init icons di event umum
        $('#reportTable').on('draw.dt page.dt length.dt search.dt', function(){ setTimeout(initIcons, 80); });

        initIcons();
    });

    function initTooltips(){
    const list = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    list.forEach(el => new bootstrap.Tooltip(el));
}

$(document).ready(function () {
    var t = $('#reportTable').DataTable({
        columnDefs: [{ searchable:false, orderable:false, targets:0 }],
        pageLength: 10, order: [],
        language: { /* ...bahasa kamu yang tadi... */ },
        drawCallback: function() { initIcons(); initTooltips(); },
        initComplete: function() { initIcons(); initTooltips(); }
    });

    t.on('order.dt search.dt', function () {
        t.column(0, { search:'applied', order:'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
        initIcons(); initTooltips();
    });

    $('#reportTable').on('draw.dt page.dt length.dt search.dt', function(){
        setTimeout(function(){ initIcons(); initTooltips(); }, 80);
    });

    initIcons(); initTooltips();
});
</script>
@endpush
