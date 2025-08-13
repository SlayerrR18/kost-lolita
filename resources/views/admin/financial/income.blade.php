@extends('layouts.main')

@section('title', 'Pemasukan')

@push('css')
<style>
  .financial-wrap{min-height:100vh;background:#f8fafc;padding:24px 0}
  /* header */
  .page-header{
    background:linear-gradient(135deg,#1a7f5a 0%,#16c79a 100%);
    border-radius:24px;padding:24px;color:#fff;
    display:flex;justify-content:space-between;align-items:flex-start;gap:16px;
    box-shadow:0 4px 20px rgba(26,127,90,.15); margin-bottom:24px;
  }
  .page-title{margin:0;font-size:1.75rem;font-weight:700}
  .subtext{opacity:.9;margin-top:6px}
  .btn-add{background:rgba(255,255,255,.12);color:#fff;border:2px solid rgba(255,255,255,.25);
           padding:.65rem 1.25rem;border-radius:12px;display:inline-flex;align-items:center;gap:.5rem}
  .btn-add:hover{background:rgba(255,255,255,.22);color:#fff;transform:translateY(-1px)}

  /* stat chips */
  .stat-grid{display:grid;gap:16px;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));margin-bottom:20px}
  .stat-card{background:#fff;border-radius:16px;padding:16px;box-shadow:0 4px 12px rgba(0,0,0,.05)}
  .stat-title{color:#64748b;font-size:.875rem;font-weight:600;margin:0 0 6px}
  .stat-value{font-size:1.35rem;font-weight:800;color:#0f172a;margin:0}
  .stat-foot{display:flex;align-items:center;gap:6px;color:#1a7f5a;font-size:.85rem;margin-top:6px}

  /* table card */
  .card{background:#fff;border-radius:16px;box-shadow:0 4px 12px rgba(0,0,0,.05)}
  .table-toolbar{display:flex;gap:12px;justify-content:space-between;align-items:center;padding:16px 16px 0}
  .filter-inline{display:flex;gap:8px;flex-wrap:wrap}
  .filter-inline .form-select,.filter-inline .form-control{border-radius:10px;border:1px solid #e2e8f0;padding:.45rem .8rem}

  .table-responsive{max-height:64vh;overflow:auto;border-radius:0 0 16px 16px}
  table{margin-bottom:0}
  thead th{position:sticky;top:0;background:#f8fafc;border-bottom:1px solid #e5e7eb;color:#64748b;
           font-size:.78rem;letter-spacing:.4px;text-transform:uppercase;padding:12px}
  tbody td{vertical-align:middle;padding:14px}
  tbody tr:hover{background:#f9fafb}

  .status-badge{padding:.4rem .8rem;border-radius:10px;font-weight:600;font-size:.8rem}
  .status-in{background:#dcfce7;color:#166534}

  .thumb{width:40px;height:40px;object-fit:cover;border-radius:8px;cursor:pointer;border:1px solid #e5e7eb}
  .actions .btn{width:34px;height:34px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center}

  .empty{padding:3rem 1rem;text-align:center}
  .empty i{width:48px;height:48px;color:#94a3b8;margin-bottom:10px}
  .btn-ghost-danger{
  background: rgba(239, 68, 68, .10);
  color:#dc2626; border:none; width:34px; height:34px;
  display:inline-flex; align-items:center; justify-content:center;
  border-radius:10px; transition:all .15s ease;
}
.btn-ghost-danger:hover{
  background: rgba(239, 68, 68, .18);
  transform: translateY(-1px);
}
.btn-ghost-danger i{ width:18px; height:18px; }

</style>
@endpush

@section('content')
<div class="financial-wrap">
  {{-- Header --}}
  <div class="page-header">
    <div>
      <h1 class="page-title">Pemasukan</h1>
      <p class="subtext mb-0">Kelola seluruh pemasukan kost</p>
    </div>
    <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
      <i data-feather="plus-circle"></i> Tambah Pemasukan
    </button>
  </div>

  {{-- Summary chips --}}
  <div class="stat-grid">
    <div class="stat-card">
      <p class="stat-title">Total Pemasukan</p>
      <p class="stat-value">Rp {{ number_format($totalIncome,0,',','.') }}</p>
      <div class="stat-foot">
        <i data-feather="trending-up" style="width:16px;height:16px"></i> Pendapatan bulan ini
      </div>
    </div>
    <div class="stat-card">
      <p class="stat-title">Jumlah Transaksi</p>
      <p class="stat-value">{{ $transactions->count() }}</p>
      <div class="stat-foot">
        <i data-feather="list" style="width:16px;height:16px"></i> Total baris data
      </div>
    </div>
    <div class="stat-card">
      <p class="stat-title">Rata-rata Per Transaksi</p>
      <p class="stat-value">
        Rp {{ number_format($transactions->count() ? floor($totalIncome / max(1,$transactions->count())) : 0,0,',','.') }}
      </p>
      <div class="stat-foot">
        <i data-feather="dollar-sign" style="width:16px;height:16px"></i> Estimasi
      </div>
    </div>
  </div>

  {{-- Table --}}
  <div class="card">
    <div class="table-toolbar">
      <div class="filter-inline">
        <input id="quickSearch" type="text" class="form-control" placeholder="Cari nama/kamar…">
      </div>
      <div class="text-muted small">
        Menampilkan {{ $transactions->count() }} transaksi
      </div>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th style="min-width:120px">No Kamar</th>
            <th>Nama Transaksi</th>
            <th style="width:140px">Tanggal</th>
            <th style="width:160px">Total</th>
            <th style="width:120px">Status</th>
            <th style="width:90px">Bukti</th>
            <th style="width:100px">Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          @forelse($transactions as $t)
            <tr>
              <td><span class="fw-semibold">Kamar {{ $t->kost->nomor_kamar ?? '-' }}</span></td>
              <td>{{ $t->nama_transaksi }}</td>
              <td>{{ optional($t->tanggal_transaksi)->format('d/m/Y') }}</td>
              <td class="fw-semibold">Rp {{ number_format($t->total,0,',','.') }}</td>
              <td><span class="status-badge status-in">{{ $t->status }}</span></td>
              <td class="text-center">
                @if($t->bukti_pembayaran_url)
                  <img src="{{ $t->bukti_pembayaran_url }}" class="thumb"
                       alt="Bukti" onclick="showImage('{{ $t->bukti_pembayaran_url }}')">
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td class="text-center actions">
                <button class="btn-ghost-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Hapus" onclick="deleteTransaction({{ $t->id }})">
                <i data-feather="x-octagon"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr><td colspan="7">
              <div class="empty">
                <i data-feather="inbox"></i>
                <div class="text-muted">Belum ada data pemasukan</div>
              </div>
            </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@include('admin.financial.partials.add-modal')
@include('admin.financial.partials.image-modal')
@include('admin.financial.partials.delete-modal')
@endsection

@push('js')
<script>
  const $ = s=>document.querySelector(s);

  document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    // quick search
    $('#quickSearch')?.addEventListener('input', function(){
      const q = this.value.toLowerCase();
      document.querySelectorAll('#tableBody tr').forEach(tr=>{
        tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  });

  function showImage(url){
    const img = document.getElementById('previewImage');
    img.src = url;
    new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
  }

  function deleteTransaction(id){
    Swal.fire({
      title:'Hapus Transaksi?',
      text:'Data yang dihapus tidak dapat dikembalikan.',
      icon:'warning', showCancelButton:true,
      confirmButtonColor:'#dc2626', cancelButtonColor:'#6b7280',
      confirmButtonText:'Ya, hapus', cancelButtonText:'Batal'
    }).then(res=>{
      if(!res.isConfirmed) return;
      fetch(`/admin/financial/${id}`, {
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
      }).then(r=>r.json()).then(d=>{
        if(d.success){ Swal.fire('Berhasil','Transaksi dihapus','success').then(()=>location.reload()); }
        else{ throw new Error(d.message||'Gagal menghapus'); }
      }).catch(e=>Swal.fire('Error', e.message, 'error'));
    });
  }

   function previewSlip(input){
    const box = document.getElementById('slipPreview');
    const img = box.querySelector('img');
    if(input.files && input.files[0]){
      img.src = URL.createObjectURL(input.files[0]);
      box.style.display = 'block';
    }else{
      box.style.display = 'none';
      img.src = '';
    }
  }
</script>
@endpush
