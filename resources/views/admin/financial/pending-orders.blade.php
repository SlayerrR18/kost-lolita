{{-- resources/views/admin/financial/pending-orders.blade.php --}}
@extends('layouts.main')

@section('title', 'Konfirmasi Pesanan')

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Konfirmasi Pesanan</h1>
                <p class="mb-0">Kelola pesanan yang menunggu konfirmasi</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light" onclick="window.location.reload()">
                    <i data-feather="refresh-cw" class="me-2"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if($pendingOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr>
                            <th>No Kamar</th>
                            <th>Nama Pemesan</th>
                            <th>Kontak</th>
                            <th class="text-center">Bukti</th>
                            <th>Tipe</th>
                            <th>Waktu</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pendingOrders as $order)
                        <tr data-row="{{ $order->id }}">
                            {{-- NO KAMAR --}}
                            <td>
                                <div class="fw-medium">Kamar {{ $order->kost->nomor_kamar ?? '-' }}</div>
                                @if($order->kost)
                                    <div class="small text-muted">
                                        Rp {{ number_format($order->kost->harga, 0, ',', '.') }}/bulan
                                    </div>
                                @endif
                            </td>

                            {{-- NAMA PEMESAN --}}
                            <td>
                                <div class="fw-medium">{{ $order->name }}</div>
                                <div class="small text-muted">{{ $order->alamat }}</div>
                            </td>

                            {{-- KONTAK --}}
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i data-feather="mail" class="text-muted" style="width:14px"></i>
                                    <span>{{ $order->email }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i data-feather="phone" class="text-muted" style="width:14px"></i>
                                    <span>{{ $order->phone }}</span>
                                </div>
                            </td>

                            {{-- BUKTI --}}
                            <td class="text-center">
                                @if($order->bukti_pembayaran_url)
                                    <button type="button" class="btn btn-sm btn-light btn-action"
                                            onclick="showImage({{ json_encode($order->bukti_pembayaran_url) }})">
                                        <i data-feather="file-text"></i> Lihat
                                    </button>
                                @else
                                    <span class="badge bg-warning">Belum Ada</span>
                                @endif
                            </td>

                            {{-- TIPE --}}
                            <td>
                                <span class="badge {{ $order->is_extension ? 'bg-warning' : 'bg-info' }}">
                                    {{ $order->is_extension ? 'Perpanjangan' : 'Baru' }}
                                </span>
                            </td>

                            {{-- WAKTU --}}
                            <td>
                                <div class="fw-medium">{{ $order->created_at->format('d M Y') }}</div>
                                <div class="small text-muted">{{ $order->created_at->format('H:i') }}</div>
                                <div class="small text-muted">{{ $order->created_at->diffForHumans() }}</div>
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button"
                                            class="btn btn-sm btn-success btn-action"
                                            onclick="showConfirmModal('{{ $order->id }}','{{ $order->is_extension ? 'extension' : 'new' }}')">
                                        <i data-feather="check"></i> Terima
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-danger btn-action"
                                            onclick="rejectOrder('{{ $order->id }}')">
                                        <i data-feather="x"></i> Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i data-feather="inbox" class="empty-state-icon"></i>
                    <h5>Tidak Ada Pesanan Pending</h5>
                    <p class="text-muted mb-0">Semua pesanan sudah dikonfirmasi</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title">Preview Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img src="" id="previewImage" class="img-fluid w-100 rounded preview-image" alt="Bukti Pembayaran">
            </div>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-bottom bg-primary text-white">
                <h5 class="modal-title">
                    <i data-feather="info" class="me-2"></i>
                    Konfirmasi Pesanan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="text-primary mb-4">
                    <i data-feather="help-circle" style="width: 64px; height: 64px;"></i>
                </div>
                <h5 class="mb-3">Konfirmasi Pesanan</h5>
                <p class="mb-0">Apakah Anda yakin ingin mengkonfirmasi pesanan ini? Akun pengguna akan dibuat secara otomatis.</p>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i data-feather="x" class="me-2"></i>
                    Batal
                </button>
                <button type="button" class="btn btn-primary" onclick="processConfirmation()">
                    <i data-feather="check" class="me-2"></i>
                    Ya, Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-bottom bg-success text-white">
                <h5 class="modal-title">
                    <i data-feather="check-circle" class="me-2"></i>
                    Pesanan Dikonfirmasi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="text-success mb-3">
                        <i data-feather="user-check" style="width: 64px; height: 64px;"></i>
                    </div>
                    <h5 class="mb-3">Pesanan Berhasil Dikonfirmasi</h5>
                </div>

                <div class="card border-0 bg-light mb-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Detail Pesanan:</h6>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td style="width: 140px"><small class="text-muted">Nama Pemesan</small></td>
                                    <td><strong id="confirmName">-</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Email</small></td>
                                    <td><strong id="confirmEmail">-</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">No. Kamar</small></td>
                                    <td><strong id="confirmRoom">-</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Durasi Sewa</small></td>
                                    <td><strong><span id="confirmDuration">-</span> Bulan</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Tanggal Masuk</small></td>
                                    <td><strong id="confirmCheckIn">-</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Tanggal Keluar</small></td>
                                    <td><strong id="confirmCheckOut">-</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 mb-0">
                    <div class="d-flex">
                        <i data-feather="info" class="me-2"></i>
                        <div>
                            <small class="d-block fw-medium">Password default telah dibuat</small>
                            <small class="text-muted">Silakan atur ulang password di menu Manajemen Akun</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <a href="{{ route('admin.account.index') }}" class="btn btn-primary">
                    <i data-feather="users" class="me-2"></i>
                    Ke Manajemen Akun
                </a>
                <button type="button" class="btn btn-light" onclick="window.location.reload()">
                    <i data-feather="refresh-cw" class="me-2"></i>
                    Muat Ulang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Confirmation Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-bottom bg-danger text-white">
                <h5 class="modal-title">
                    <i data-feather="x-circle" class="me-2"></i>
                    Tolak Pesanan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-4">
                    <i data-feather="alert-triangle" style="width: 64px; height: 64px;"></i>
                </div>
                <h5 class="mb-3">Konfirmasi Penolakan</h5>
                <p class="mb-0">Apakah Anda yakin ingin menolak pesanan ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i data-feather="x" class="me-2"></i>
                    Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmReject">
                    <i data-feather="trash-2" class="me-2"></i>
                    Ya, Tolak
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .main-content{padding:2rem;background:#f8fafc;min-height:100vh}
    .page-header{background:linear-gradient(135deg,#1a7f5a 0%,#16c79a 100%);border-radius:24px;padding:2rem;margin-bottom:2rem;color:#fff;box-shadow:0 4px 20px rgba(26,127,90,.15)}
    .page-title{font-size:1.75rem;font-weight:700;margin:0}
    .card{border-radius:16px;overflow:hidden}
    .table{margin:0}
    .table th{background:#f8fafc;font-size:.875rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.5px;padding:1rem}
    .table td{padding:1rem;vertical-align:middle}
    .table tbody tr{transition:all .2s ease}
    .table tbody tr:hover{background:#f1f5f9}
    .btn-action{padding:.5rem 1rem;border-radius:8px;font-weight:500;display:inline-flex;align-items:center;gap:.5rem;transition:all .2s}
    .btn-action:hover{transform:translateY(-2px)}
    .btn-action i{width:16px;height:16px}
    .btn-success{background:#16a34a;border-color:#16a34a}
    .btn-success:hover{background:#15803d;border-color:#15803d}
    .btn-danger{background:#dc2626;border-color:#dc2626}
    .btn-danger:hover{background:#b91c1c;border-color:#b91c1c}
    .empty-state{text-align:center;padding:4rem 2rem}
    .empty-state-icon{width:64px;height:64px;color:#94a3b8;margin-bottom:1.5rem}
    .modal-content{border-radius:16px;border:none}
    .modal-header{padding:1.5rem}
    .modal-body{padding:2rem}
    .modal-footer{padding:1.5rem}
    .preview-image{border-radius:12px;width:100%;height:auto;transition:transform .3s}
    .preview-image:hover{transform:scale(1.02)}
</style>
@endpush

@push('js')
<script>
(() => {
  let current = { id: null, type: 'new' };

  const $  = s => document.querySelector(s);
  const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

  const formatID = iso => {
    if (!iso) return '-';
    try {
      return new Date(iso + 'T00:00:00').toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' });
    } catch { return iso; }
  };

  const setLoading = (btn, on, text='Memproses...') => {
    if (!btn) return;
    if (on) {
      btn.dataset._inner = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>${text}`;
    } else {
      btn.disabled = false;
      btn.innerHTML = btn.dataset._inner || btn.innerHTML;
    }
  };

  // Preview bukti pembayaran
  window.showImage = url => {
    $('#previewImage').src = url;
    new bootstrap.Modal($('#imagePreviewModal')).show();
  };

  // Buka modal konfirmasi, set judul/teks sesuai tipe
  window.showConfirmModal = (orderId, type = 'new') => {
    current.id = orderId;
    current.type = type;

    const title = $('#confirmModal .modal-title');
    const body  = $('#confirmModal .modal-body p');

    if (type === 'extension') {
      title.innerHTML = `<i data-feather="info" class="me-2"></i> Konfirmasi Perpanjangan`;
      body.textContent = 'Konfirmasi perpanjangan kontrak? Akun pengguna tidak akan dibuat ulang.';
    } else {
      title.innerHTML = `<i data-feather="info" class="me-2"></i> Konfirmasi Pesanan`;
      body.textContent = 'Konfirmasi pesanan baru? Akun pengguna akan dibuat otomatis jika belum ada.';
    }
    feather.replace();
    new bootstrap.Modal($('#confirmModal')).show();
  };

  // Proses konfirmasi
  window.processConfirmation = async () => {
    if (!current.id) return;
    const btn = $('#confirmModal .btn-primary');
    setLoading(btn, true);

    try {
      const res  = await fetch(`/admin/financial/orders/${current.id}/confirm`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' }
      });
      const data = await res.json();
      if (!res.ok || !data.success) throw new Error(data.message || 'Gagal mengonfirmasi');

      const d = data.data;
      $('#confirmName').textContent     = d.name ?? '-';
      $('#confirmEmail').textContent    = d.email ?? '-';
      $('#confirmRoom').textContent     = d.room_number ?? '-';
      $('#confirmDuration').textContent = d.duration ?? '-';
      $('#confirmCheckIn').textContent  = formatID(d.tanggal_masuk);
      $('#confirmCheckOut').textContent = formatID(d.tanggal_keluar);

      bootstrap.Modal.getInstance($('#confirmModal')).hide();
      new bootstrap.Modal($('#successModal')).show();

      // Hapus baris dari tabel tanpa reload
      const row = document.querySelector(`[data-row="${current.id}"]`);
      if (row) row.remove();

    } catch (err) {
      alert(err.message);
    } finally {
      setLoading(btn, false);
    }
  };

  // Buka modal tolak
  window.rejectOrder = orderId => {
    current.id = orderId;
    new bootstrap.Modal($('#rejectModal')).show();
  };

  // Proses tolak
  $('#confirmReject')?.addEventListener('click', async function () {
    if (!current.id) return;
    setLoading(this, true);

    try {
      const res  = await fetch(`/admin/financial/orders/${current.id}/reject`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' }
      });
      const data = await res.json();
      if (!res.ok || !data.success) throw new Error(data.message || 'Gagal menolak pesanan');

      const row = document.querySelector(`[data-row="${current.id}"]`);
      if (row) row.remove();
      bootstrap.Modal.getInstance($('#rejectModal')).hide();

    } catch (err) {
      alert(err.message);
    } finally {
      setLoading(this, false);
    }
  });

  document.addEventListener('DOMContentLoaded', () => feather.replace());
})();
</script>
@endpush
