@extends('layouts.main')

@section('title', 'Manajemen Kamar')

@push('css')
<style>
  :root{
    --primary:#1a7f5a; --primary2:#16c79a;
    --danger:#dc2626;  --muted:#64748b; --bg:#f8fafc;
  }

  .kost-wrap{min-height:100vh;background:var(--bg);padding:2rem}

  /* Header */
  .page-header{
    background:linear-gradient(135deg,var(--primary) 0%,var(--primary2) 100%);
    border-radius:24px;color:#fff;padding:1.6rem 1.8rem;margin-bottom:1.25rem;
    display:flex;justify-content:space-between;align-items:center;gap:1rem;
    box-shadow:0 4px 20px rgba(26,127,90,.12)
  }
  .page-title{margin:0;font-weight:800;font-size:1.6rem}
  .btn-add{
    background:rgba(255,255,255,.15); color:#fff; border:2px solid rgba(255,255,255,.25);
    border-radius:12px; padding:.7rem 1.2rem; font-weight:600; display:inline-flex; gap:.5rem; align-items:center
  }
  .btn-add:hover{background:rgba(255,255,255,.22); transform:translateY(-1px)}

  /* Stats */
  .stats{display:grid; gap:14px; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); margin-bottom:1rem}
  .stat{
    background:#fff;border-radius:16px;padding:1rem 1.2rem; box-shadow:0 4px 12px rgba(0,0,0,.05);
  }
  .stat .k{color:#94a3b8;font-weight:700;font-size:.8rem;letter-spacing:.08em;text-transform:uppercase}
  .stat .v{color:#0f172a;font-weight:800;font-size:1.6rem;margin-top:.15rem}

  /* Filter bar */
  .filters{display:flex; gap:.6rem; margin-bottom:1rem; flex-wrap:wrap}
  .filters .form-control, .filters .form-select{
    border:2px solid #e2e8f0; background:#fff; border-radius:10px; padding:.55rem .9rem
  }
  .filters .form-control:focus, .filters .form-select:focus{
    border-color:var(--primary); box-shadow:0 0 0 3px rgba(26,127,90,.12)
  }

  /* Table Card */
  .card{
    background:#fff;border-radius:20px; box-shadow:0 6px 18px rgba(0,0,0,.06); overflow:hidden
  }
  table thead th{
    background:#f8fafc; color:#64748b; text-transform:uppercase; letter-spacing:.04em;
    font-size:.8rem; padding:1rem
  }
  table td{padding:1rem; vertical-align:middle}

  .room-img{width:96px;height:96px;border-radius:12px;object-fit:cover; cursor:pointer; transition:transform .2s}
  .room-img:hover{transform:scale(1.04)}

  .badge-status{
    display:inline-flex;align-items:center;gap:.45rem;border-radius:10px;padding:.4rem .75rem;font-weight:700
  }
  .is-terisi{background:#dcfce7;color:#166534}
  .is-kosong{background:#fee2e2;color:#991b1b}

  .tags{display:flex;gap:.4rem;flex-wrap:wrap;max-width:420px}
  .tag{background:#f1f5f9; color:#0f766e; border-radius:8px; padding:.28rem .6rem; font-size:.85rem; display:inline-flex; align-items:center; gap:.35rem}

  /* Ghost action buttons */
  .btn-ghost{width:36px;height:36px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;border:none;transition:.15s}
  .btn-ghost:hover{transform:translateY(-1px)}
  .btn-ghost-primary{background:rgba(26,127,90,.10); color:var(--primary)}
  .btn-ghost-primary:hover{background:rgba(26,127,90,.18)}
  .btn-ghost-danger{background:rgba(239,68,68,.10); color:#dc2626}
  .btn-ghost-danger:hover{background:rgba(239,68,68,.18)}
  .btn-ghost i{width:18px;height:18px}

  /* Row fade-in */
  tbody tr{opacity:0; animation:fadeInUp .35s ease forwards}
  @keyframes fadeInUp{from{opacity:0; transform:translateY(8px)} to{opacity:1; transform:none}}

  @media (max-width:768px){ .kost-wrap{padding:1rem} .page-header{border-radius:18px} }
</style>
@endpush

@section('content')
<div class="kost-wrap">
  {{-- Header --}}
  <div class="page-header">
    <h1 class="page-title">Manajemen Kamar</h1>
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
  <div class="stats">
    <div class="stat"><div class="k">Total Kamar</div><div class="v">{{ $total }}</div></div>
    <div class="stat"><div class="k">Kamar Terisi</div><div class="v">{{ $terisi }}</div></div>
    <div class="stat"><div class="k">Kamar Kosong</div><div class="v">{{ $kosong }}</div></div>
  </div>

  {{-- Filter bar --}}
  <form method="GET" action="{{ route('admin.kost.index') }}" class="filters">
    <input type="text" name="search" class="form-control" placeholder="Cari nomor kamar…" value="{{ request('search') }}">
    <select name="status" class="form-select">
      <option value="">Semua Status</option>
      <option value="Kosong" {{ request('status')=='Kosong'?'selected':'' }}>Kosong</option>
      <option value="Terisi" {{ request('status')=='Terisi'?'selected':'' }}>Terisi</option>
    </select>
    <select name="price" class="form-select">
      <option value="">Semua Harga</option>
      <option value="asc"  {{ request('price')=='asc'?'selected':'' }}>Harga: Rendah → Tinggi</option>
      <option value="desc" {{ request('price')=='desc'?'selected':'' }}>Harga: Tinggi → Rendah</option>
    </select>
    <button class="btn btn-primary">Terapkan</button>
  </form>

  {{-- Table --}}
  <div class="card">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Nomor Kamar</th>
            <th>Fasilitas</th>
            <th>Foto</th>
            <th>Harga</th>
            <th>Status</th>
            <th style="width:110px">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($kosts as $i => $kost)
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
                if (is_array($kost->foto) && count($kost->foto))     $src = asset('storage/'.$kost->foto[0]);
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

            <td>
              <div class="d-flex gap-1">
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
        @empty
          <tr><td colspan="6" class="text-center text-muted py-5">
            <i data-feather="inbox"></i> Belum ada data kamar
          </td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
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
