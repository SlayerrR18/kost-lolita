@extends('layouts.admin')

@section('title', 'Manajemen Kamar')

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

    .kost-wrap {
        min-height: 100vh;
        background: var(--bg);
        padding: 2rem;
    }

    /* === Header === */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        border-radius: var(--radius-lg);
        color: #fff;
        padding: 2rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 4px 20px rgba(26, 127, 90, .15);
        flex-wrap: wrap;
    }

    .page-header-content {
        flex-grow: 1;
    }

    .page-title {
        margin: 0;
        font-weight: 700;
        font-size: 1.75rem;
    }

    .page-subtitle {
        opacity: 0.9;
        margin-top: 0.5rem;
    }

    .btn-add {
        background: rgba(255, 255, 255, .15);
        color: #fff;
        border: 2px solid rgba(255, 255, 255, .25);
        border-radius: var(--radius-md);
        padding: .75rem 1.5rem;
        font-weight: 600;
        display: inline-flex;
        gap: .5rem;
        align-items: center;
        transition: all .2s ease;
        text-decoration: none;
    }

    .btn-add:hover {
        background: rgba(255, 255, 255, .22);
        transform: translateY(-1px);
        color: #fff;
    }

    /* === Stats === */
    .stats-grid {
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
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-label {
        color: var(--muted);
        font-weight: 600;
        font-size: .875rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin: 0;
    }

    .stat-value {
        color: var(--ink);
        font-weight: 800;
        font-size: 1.75rem;
        margin-top: .15rem;
    }

    /* === Filter Bar === */
    .filter-section {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        align-items: center;
        padding: 1rem;
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
    }

    .filter-input-group {
        position: relative;
    }

    .filter-input-group .form-control,
    .filter-input-group .form-select {
        border: 1px solid var(--ring);
        background: var(--bg);
        border-radius: var(--radius-md);
        padding: 0.55rem 0.9rem;
        font-size: .875rem;
        color: var(--ink);
        transition: all 0.2s ease;
        min-width: 160px;
    }

    .filter-input-group .form-control:focus,
    .filter-input-group .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(26, 127, 90, .12);
        outline: none;
        background: var(--surface);
    }

    .btn-apply-filter {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        padding: 0.55rem 1.25rem;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-apply-filter:hover {
        background: var(--primary-2);
        transform: translateY(-1px);
    }

    /* === Table Card === */
    .card-base {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }

    .table-responsive {
        max-height: 70vh;
        overflow-y: auto;
        padding: 1rem;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: var(--bg);
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .04em;
        font-size: .75rem;
        padding: 1rem;
        border-bottom: 1px solid var(--ring);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--ring);
    }

    .table tbody tr:hover {
        background-color: #f1f5f9;
    }

    .room-img {
        width: 64px;
        height: 64px;
        border-radius: var(--radius-md);
        object-fit: cover;
        cursor: pointer;
        transition: transform .2s;
        border: 1px solid var(--ring);
    }

    .room-img:hover {
        transform: scale(1.04);
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        border-radius: var(--radius-sm);
        padding: .4rem .75rem;
        font-weight: 600;
        font-size: .85rem;
    }

    .is-terisi { background: #dcfce7; color: var(--success); }
    .is-kosong { background: #fee2e2; color: var(--danger); }

    .tags {
        display: flex;
        gap: .4rem;
        flex-wrap: wrap;
        max-width: 420px;
    }

    .tag {
        background: var(--secondary);
        color: var(--primary);
        border-radius: var(--radius-sm);
        padding: .28rem .6rem;
        font-size: .85rem;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
    }

    /* Ghost action buttons */
    .btn-ghost {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-md);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: .15s;
    }

    .btn-ghost:hover {
        transform: translateY(-1px);
    }

    .btn-ghost-primary {
        background: rgba(26, 127, 90, .10);
        color: var(--primary);
    }

    .btn-ghost-primary:hover {
        background: rgba(26, 127, 90, .18);
    }

    .btn-ghost-danger {
        background: rgba(239, 68, 68, .10);
        color: var(--danger);
    }

    .btn-ghost-danger:hover {
        background: rgba(239, 68, 68, .18);
    }

    .btn-ghost i {
        width: 18px;
        height: 18px;
    }

    /* Row fade-in */
    tbody tr {
        opacity: 0;
        animation: fadeInUp .35s ease forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(8px)
        }
        to {
            opacity: 1;
            transform: none
        }
    }

    @media (max-width: 768px) {
        .kost-wrap {
            padding: 1rem;
        }
        .page-header {
            border-radius: 18px;
            flex-direction: column;
            align-items: flex-start;
            padding: 1.5rem;
        }
        .btn-add {
            width: 100%;
            justify-content: center;
        }
        .filter-section {
            flex-direction: column;
        }
        .filter-input-group {
            width: 100%;
        }
        .filter-input-group .form-control,
        .filter-input-group .form-select {
            min-width: unset;
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="kost-wrap">
    {{-- Header --}}
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">Manajemen Kamar</h1>
            <p class="page-subtitle">Kelola daftar kamar kost, fasilitas, dan status penghuni.</p>
        </div>
        <a href="{{ route('admin.kost.create') }}" class="btn btn-add">
            <i data-feather="plus-circle"></i> Tambah Kamar
        </a>
    </div>

    {{-- Stats --}}
    @php
        $total   = $kosts->count();
        $terisi  = $kosts->where('status','Terisi')->count();
        $kosong  = $kosts->where('status','Kosong')->count();
    @endphp
    <div class="stats-grid">
        <div class="stat-card"><div class="stat-label">Total Kamar</div><div class="stat-value">{{ $total }}</div></div>
        <div class="stat-card"><div class="stat-label">Kamar Terisi</div><div class="stat-value">{{ $terisi }}</div></div>
        <div class="stat-card"><div class="stat-label">Kamar Kosong</div><div class="stat-value">{{ $kosong }}</div></div>
    </div>

    {{-- Filter bar --}}
    <form method="GET" action="{{ route('admin.kost.index') }}" class="filter-section">
        <div class="filter-input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nomor kamar…" value="{{ request('search') }}">
        </div>
        <div class="filter-input-group">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="Kosong" {{ request('status')=='Kosong'?'selected':'' }}>Kosong</option>
                <option value="Terisi" {{ request('status')=='Terisi'?'selected':'' }}>Terisi</option>
            </select>
        </div>
        <div class="filter-input-group">
            <select name="price" class="form-select">
                <option value="">Semua Harga</option>
                <option value="asc"  {{ request('price')=='asc'?'selected':'' }}>Harga: Rendah → Tinggi</option>
                <option value="desc" {{ request('price')=='desc'?'selected':'' }}>Harga: Tinggi → Rendah</option>
            </select>
        </div>
        <button class="btn btn-apply-filter" type="submit">
            <i data-feather="filter"></i> Terapkan
        </button>
    </form>

    {{-- Table --}}
    <div class="card-base">
        @if ($kosts->isEmpty())
            <div class="text-center p-5">
                <i data-feather="inbox" class="text-muted" style="width: 48px; height: 48px;"></i>
                <div class="text-muted mt-3">Tidak ada data kamar yang ditemukan.</div>
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nomor Kamar</th>
                            <th>Fasilitas</th>
                            <th>Foto</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th style="width:110px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($kosts as $i => $kost)
                        <tr style="animation-delay: {{ $i * 60 }}ms">
                            <td class="fw-bold">#{{ $kost->nomor_kamar }}</td>
                            <td>
                                @php
                                    $fasilitas = is_string($kost->fasilitas) ? json_decode($kost->fasilitas, true) : $kost->fasilitas;
                                    $fasilitas = is_array($fasilitas) ? $fasilitas : [];
                                    $show = array_slice($fasilitas, 0, 4);
                                    $more = max(count($fasilitas) - 4, 0);
                                @endphp
                                <div class="tags">
                                    @foreach($show as $f)
                                        <span class="tag"><i data-feather="check-circle" style="width:14px;height:14px"></i>{{ $f }}</span>
                                    @endforeach
                                    @if($more > 0)
                                        <span class="tag" title="{{ implode(', ', $fasilitas) }}">+{{ $more }} lagi</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $src = null;
                                    if (is_array($kost->foto) && count($kost->foto))      $src = asset('storage/'.$kost->foto[0]);
                                    elseif (is_string($kost->foto) && trim($kost->foto)) $src = asset('storage/'.$kost->foto);
                                @endphp
                                @if($src)
                                    <img src="{{ $src }}" class="room-img" alt="Kamar {{ $kost->nomor_kamar }}" data-preview="{{ $src }}">
                                @else
                                    <span class="text-muted">Tidak ada foto</span>
                                @endif
                            </td>
                            <td class="fw-bold">Rp {{ number_format($kost->harga,0,',','.') }}</td>
                            <td>
                                <span class="badge-status {{ $kost->status=='Terisi' ? 'is-terisi' : 'is-kosong' }}">
                                    <i data-feather="{{ $kost->status=='Terisi' ? 'user-check' : 'home' }}"></i>
                                    {{ $kost->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.kost.edit', $kost->id) }}" class="btn-ghost btn-ghost-primary" data-bs-toggle="tooltip" title="Edit kamar">
                                        <i data-feather="edit-2"></i>
                                    </a>
                                    <button type="button" class="btn-ghost btn-ghost-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $kost->id }}" title="Hapus kamar">
                                        <i data-feather="x-octagon"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Delete modal per item --}}
                        <div class="modal fade" id="deleteModal{{ $kost->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body text-center p-4">
                                        <div class="text-danger mb-3"><i data-feather="x-octagon" style="width:64px;height:64px"></i></div>
                                        <h5 class="mb-2 text-danger">Hapus Kamar</h5>
                                        <p class="text-muted mb-3">Yakin hapus kamar #{{ $kost->nomor_kamar }}? Tindakan ini tidak dapat dibatalkan.</p>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                <i data-feather="x" class="me-1"></i>Batal
                                            </button>
                                            <form action="{{ route('admin.kost.destroy', $kost->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i data-feather="x-octagon" class="me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Preview image modal (satu saja) --}}
    <div class="modal fade" id="imgPreview" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <button type="button" class="btn-close ms-auto m-2" data-bs-dismiss="modal"></button>
                <img id="imgPreviewSrc" class="img-fluid rounded-bottom" alt="Preview">
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.feather) feather.replace();

    // tooltip
    [...document.querySelectorAll('[data-bs-toggle="tooltip"]')]
        .forEach(el => new bootstrap.Tooltip(el));

    // preview
    const modal = new bootstrap.Modal('#imgPreview');
    const img  = document.getElementById('imgPreviewSrc');
    document.querySelectorAll('.room-img').forEach(el=>{
        el.addEventListener('click', ()=>{
            img.src = el.dataset.preview || el.src;
            modal.show();
        });
    });

    // animate rows delay already inline; just re-run icons after modal show
    document.querySelectorAll('.modal').forEach(m=>{
        m.addEventListener('shown.bs.modal', ()=> feather.replace());
    });
});
</script>
@endpush
