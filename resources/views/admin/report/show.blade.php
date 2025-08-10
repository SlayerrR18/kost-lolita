{{-- resources/views/admin/reports/show.blade.php --}}
@extends('layouts.main')

@section('title','Detail Report')

@push('css')
<style>
  .report-admin-wrap{min-height:100vh;background:#f8fafc;padding:2rem}
  .page-header{background:linear-gradient(135deg,#1a7f5a 0%,#16c79a 100%);color:#fff;border-radius:24px;padding:1.75rem;margin-bottom:1.5rem;box-shadow:0 4px 20px rgba(26,127,90,.15)}
  .page-title{font-size:1.6rem;font-weight:800;margin:0}
  .page-sub{opacity:.92;margin:.35rem 0 0}

  .card-soft{background:#fff;border-radius:20px;box-shadow:0 8px 24px rgba(0,0,0,.06);}
  .card-soft .card-body{padding:1.5rem 1.5rem}
  .section-title{font-weight:700;color:#0f172a;margin-bottom:1rem}

  .detail-grid{display:grid;grid-template-columns:220px 1fr;gap:.4rem 1.25rem}
  .detail-term{color:#64748b;font-weight:600}
  .detail-desc{color:#0f172a}
  .detail-divider{height:1px;background:#e2e8f0;margin:1rem 0}

  .badge{padding:.4rem .8rem;border-radius:8px;font-weight:600;font-size:.875rem}
  .badge.open{background:#fef3c7;color:#92400e}
  .badge.in_progress{background:#dbeafe;color:#1e40af}
  .badge.resolved{background:#dcfce7;color:#166534}
  .badge.muted{background:#f1f5f9;color:#475569}

  .thumb{display:inline-flex;align-items:center;gap:.5rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:.5rem .75rem}
  .thumb img{max-height:56px;border-radius:10px}
  .thumb .link{text-decoration:underline}

  .form-label{font-weight:600;color:#0f172a}
  .form-control,.form-select{border:2px solid #e2e8f0;border-radius:12px;padding:.8rem .95rem;transition:border .2s, box-shadow .2s}
  .form-control:focus,.form-select:focus{border-color:#1a7f5a;box-shadow:0 0 0 4px rgba(26,127,90,.12)}
  .counter{font-size:.85rem;color:#64748b}
  .counter.too-much{color:#dc2626;font-weight:700}

  .action-bar{display:flex;gap:.5rem;justify-content:flex-end;border-top:1px solid #e2e8f0;padding-top:1rem;margin-top:1rem}
  .btn-success{border-radius:12px;font-weight:700;padding:.75rem 1.25rem}
  .btn-outline-secondary{border-radius:12px;padding:.75rem 1.05rem}

  @media (max-width:992px){
    .detail-grid{grid-template-columns:1fr}
  }
  @media (max-width:768px){
    .report-admin-wrap{padding:1rem}
    .page-header{padding:1.25rem}
  }
</style>
@endpush

@section('content')
<div class="report-admin-wrap">
  {{-- Header --}}
  <div class="page-header">
    <h1 class="page-title">Detail Report</h1>
    <p class="page-sub">Kelola keluhan pengguna dengan sopan, cepat, dan tanpa drama.</p>
  </div>

  {{-- Detail --}}
  <div class="card card-soft mb-3">
    <div class="card-body">
      <h5 class="section-title">Informasi Laporan</h5>

      @include('layouts.alert')

      <div class="detail-grid">
        <div class="detail-term">User</div>
        <div class="detail-desc">{{ $report->user->name ?? '-' }}</div>

        <div class="detail-term">Tanggal</div>
        <div class="detail-desc">{{ \Carbon\Carbon::parse($report->date ?? $report->created_at)->format('d/m/Y') }}</div>

        <div class="detail-term">Status</div>
        <div class="detail-desc">
          <span class="badge {{ $report->status ?? 'muted' }}">
            {{ \Illuminate\Support\Str::headline($report->status ?? 'menunggu') }}
          </span>
        </div>

        <div class="detail-term">Handler</div>
        <div class="detail-desc">{{ $report->handler->name ?? '-' }}</div>
      </div>

      <div class="detail-divider"></div>

      <div class="detail-grid">
        <div class="detail-term">Isi</div>
        <div class="detail-desc">
          {{ $report->message }}
        </div>

        <div class="detail-term">Foto</div>
        <div class="detail-desc">
          @if($report->photo)
            @php $url = asset('storage/'.$report->photo); @endphp
            <div class="thumb">
              <img src="{{ $url }}" alt="Foto laporan" />
              <a href="{{ $url }}" target="_blank" class="link">Buka ukuran penuh</a>
              <button type="button" class="btn btn-sm btn-outline-secondary" onclick="showImage(`{{ $url }}`)">
                Lihat di modal
              </button>
            </div>
          @else
            <span class="badge muted">Tidak ada foto</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Tindak Lanjut --}}
  <div class="card card-soft">
    <div class="card-body">
      <h5 class="section-title">Tindak Lanjut Admin</h5>

      <form method="POST" action="{{ route('admin.reports.update', $report) }}" id="actionForm">
        @csrf @method('PUT')

        <div class="row g-3">
          <div class="col-lg-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              @foreach(['open'=>'Open','in_progress'=>'In Progress','resolved'=>'Resolved'] as $k=>$v)
                <option value="{{ $k }}" @selected($report->status===$k)>{{ $v }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-8">
            <label class="form-label">Respon</label>
            <textarea name="response" rows="4" class="form-control" placeholder="Tulis jawaban atau solusi..." maxlength="2000" aria-describedby="respCount">{{ old('response', $report->response) }}</textarea>
            <div id="respCount" class="counter mt-1">0/2000</div>
          </div>
        </div>

        <div class="action-bar">
          <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Kembali</a>
          <button class="btn btn-success" id="saveBtn">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Preview Foto --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Preview Foto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body text-center">
        <img id="previewImg" src="" alt="Foto" class="img-fluid rounded">
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
  // Counter untuk textarea respon
  (function(){
    const ta = document.querySelector('textarea[name="response"]');
    const counter = document.getElementById('respCount');
    const max = 2000;
    if (ta && counter) {
      const upd = () => {
        const len = ta.value.length;
        counter.textContent = `${len}/${max}`;
        counter.classList.toggle('too-much', len > max);
      };
      upd();
      ta.addEventListener('input', upd);
    }

    // Disable tombol saat submit biar tidak double submit
    const form = document.getElementById('actionForm');
    const btn = document.getElementById('saveBtn');
    if (form && btn) {
      form.addEventListener('submit', function(){
        btn.disabled = true;
        btn.style.opacity = .85;
        btn.textContent = 'Menyimpan...';
      });
    }
  })();

  // Modal image preview
  function showImage(url){
    const img = document.getElementById('previewImg');
    img.src = url;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
  }
</script>
@endpush
