{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.main')

@push('css')
<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
<style>
    .account-container{padding:2rem;background:#f8fafc;min-height:100vh}
    .page-header{background:linear-gradient(135deg,#1a7f5a 0%,#16c79a 100%);border-radius:24px;padding:2rem;margin-bottom:2rem;color:#fff;box-shadow:0 4px 20px rgba(26,127,90,.15)}
    .header-content{display:flex;justify-content:space-between;align-items:center}
    .page-title{font-size:1.75rem;font-weight:700;margin:0}
    .page-subtitle{opacity:.9;margin-top:.5rem}
    .content-card{background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);overflow:hidden}
    .table-container{padding:1.5rem}
    .btn-add{background:linear-gradient(135deg,#1a7f5a 0%,#16c79a 100%);color:#fff;padding:.75rem 1.5rem;border-radius:12px;font-weight:500;border:none;box-shadow:0 4px 12px rgba(26,127,90,.15)}

    .badge{padding:.4rem .8rem;border-radius:8px;font-weight:500;font-size:.875rem}
    .badge.open{background:#fef3c7;color:#92400e}
    .badge.in_progress{background:#dbeafe;color:#1e40af}
    .badge.resolved{background:#dcfce7;color:#166534}

    /* Icon buttons */
    .btn-icon{width:36px;height:36px;border-radius:10px;border:none;display:inline-flex;align-items:center;justify-content:center;transition:all .2s}
    .btn-icon i{pointer-events:none}
    .btn-icon.view{background:rgba(26,127,90,.10);color:#1a7f5a}
    .btn-icon.view:hover{background:rgba(26,127,90,.18);transform:translateY(-1px)}
    .btn-icon.download{background:#eef2f7;color:#0f172a}
    .btn-icon.download:hover{background:#e3e9f0;transform:translateY(-1px)}
    .btn-icon.detail{background:rgba(59,130,246,.12);color:#1d4ed8}
    .btn-icon.detail:hover{background:rgba(59,130,246,.18);transform:translateY(-1px)}
    .btn-icon.delete{background:rgba(220,38,38,.10);color:#dc2626}
    .btn-icon.delete:hover{background:rgba(220,38,38,.18);transform:translateY(-1px)}
</style>
@endpush

@section('title','Report Penghuni')

@section('content')
<div class="account-container">
    <div class="page-header">
        <div class="header-content">
            <div>
                <h1 class="page-title">Report Penghuni</h1>
                <p class="page-subtitle">Daftar semua masukan dan keluhan dari penghuni.</p>
            </div>
            <form class="d-flex gap-2" method="get">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama/isi">
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach(['open'=>'Open','in_progress'=>'In Progress','resolved'=>'Resolved'] as $k=>$v)
                        <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                    @endforeach
                </select>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                <button class="btn btn-add">Filter</button>
            </form>
        </div>
    </div>

    <div class="content-card">
        <div class="table-container">
            @include('layouts.alert')
            <div class="table-responsive">
                <table class="table align-middle" id="reportTable">
                    <thead>
                        <tr>
                            <th style="width:60px">No</th>
                            <th>User</th>
                            <th>Isi</th>
                            <th>Tanggal</th>
                            <th style="width:110px;">Foto</th>
                            <th>Status</th>
                            <th>Handler</th>
                            <th style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $r->user->name ?? '-' }}</td>
                            <td>{{ Str::limit($r->message, 120) }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->date ?? $r->created_at)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @if($r->photo)
                                    @php $img = asset('storage/'.$r->photo); @endphp
                                    <div class="d-inline-flex gap-1">
                                        <button type="button"
                                                class="btn-icon view"
                                                onclick="showImage(`{{ $img }}`)"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Lihat foto"
                                                aria-label="Lihat foto">
                                            <i data-feather="eye"></i>
                                        </button>
                                        <a href="{{ $img }}"
                                           class="btn-icon download"
                                           download
                                           data-bs-toggle="tooltip"
                                           data-bs-title="Unduh"
                                           aria-label="Unduh foto">
                                            <i data-feather="download"></i>
                                        </a>
                                    </div>
                                @else
                                    <span class="badge">Tidak Ada Foto</span>
                                @endif
                            </td>
                            <td><span class="badge {{ $r->status }}">{{ Str::headline($r->status) }}</span></td>
                            <td>{{ $r->handler->name ?? '-' }}</td>
                            <td>
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.reports.show', $r) }}"
                                       class="btn-icon detail"
                                       data-bs-toggle="tooltip"
                                       data-bs-title="Detail"
                                       aria-label="Detail">
                                        <i data-feather="info"></i>
                                    </a>
                                    <form action="{{ route('admin.reports.destroy', $r) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus report ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn-icon delete"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Hapus"
                                                aria-label="Hapus">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $reports->links() }}
                </div>
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
    function renumber(table){
        table.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }

    $(function(){
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
            drawCallback:function(){ initIcons(); initTooltips(); renumber(this.api()); },
            initComplete:function(){ initIcons(); initTooltips(); renumber(this.api()); }
        });

        // jaga-jaga untuk event lain
        $('#reportTable').on('page.dt length.dt search.dt order.dt', function(){
            setTimeout(function(){ initIcons(); initTooltips(); renumber(t); }, 80);
        });

        initIcons(); initTooltips();
    });

    // Modal preview image: hapus modal lama agar tidak numpuk
    function showImage(url) {
        const old = document.getElementById('imageModal');
        if (old) old.remove();

        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'imageModal';
        modal.tabIndex = -1;
        modal.innerHTML = `
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Lihat Foto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="previewImage" src="${url}" alt="Image" class="img-fluid rounded" />
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        new bootstrap.Modal(modal).show();
    }
</script>
@endpush
