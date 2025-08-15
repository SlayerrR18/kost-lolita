{{-- resources/views/user/contract/index.blade.php --}}
@extends('layouts.user')

@section('title', 'Kontrak Saya')

@push('css')
<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<style>
    /* === Palette & base (selaras dengan menu Report) === */
    :root{
        --primary:#1a7f5a; --primary-2:#16c79a;
        --surface:#ffffff; --bg:#f8fafc; --ink:#1e293b;
        --muted:#475569; --ring:#e2e8f0;
    }

    .report-container{padding:2rem;background:var(--bg);min-height:100vh}
    @media (max-width:768px){ .report-container{padding:1rem} }

    .page-header{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-2) 100%);
        border-radius:24px;padding:2rem;margin-bottom:2rem;color:#fff;box-shadow:0 4px 20px rgba(26,127,90,.15)}
    .header-content{display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}
    .page-title{font-size:1.75rem;font-weight:700;margin:0}
    .page-subtitle{opacity:.9;margin-top:.5rem}

    .content-card{background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);overflow:hidden}
    .section-card{background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);padding:1.5rem}

    .btn-add{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-2) 100%);color:#fff;padding:.75rem 1.5rem;border-radius:12px;font-weight:500;display:inline-flex;align-items:center;gap:.5rem;transition:all .3s ease;border:none;box-shadow:0 4px 12px rgba(26,127,90,.15)}
    .btn-add:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(26,127,90,.2);color:#fff}
    .btn-secondary{background:#f1f5f9;color:#0f172a;padding:.75rem 1.25rem;border-radius:12px;border:none;display:inline-flex;align-items:center;gap:.5rem;transition:all .25s ease}
    .btn-secondary:hover{background:#e7eef6;transform:translateY(-2px)}

    .badge{padding:.4rem .8rem;border-radius:8px;font-weight:500;font-size:.875rem}
    .badge.muted{background:#f1f5f9;color:#475569}
    .badge.info{background:#e0f2fe;color:#075985}
    .badge.open{background:#fef3c7;color:#92400e}
    .badge.in_progress{background:#dbeafe;color:#1e40af}
    .badge.resolved{background:#dcfce7;color:#166534}
    /* tambahan untuk status kontrak */
    .badge.active{background:#dcfce7;color:#166534}
    .badge.pending{background:#fef3c7;color:#92400e}
    .badge.expired{background:#fee2e2;color:#991b1b}

    /* Grid dua kolom yang responsif */
    .grid-layout{display:grid;grid-template-columns:350px 1fr;gap:1.5rem}
    @media (max-width:1024px){ .grid-layout{grid-template-columns:1fr} }

    /* Kartu profil kiri */
    .profile-card{padding:1.5rem}
    .profile-header{display:flex;gap:16px;align-items:center;margin-bottom:1rem}
    .profile-image {
        width: 72px;
        height: 72px;
        border-radius: 16px;
        object-fit: cover;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    /* Fallback jika gambar gagal load */
    .profile-image[src*="placeholder.jpg"] {
        opacity: 0.5;
    }

    .profile-info h3{font-size:1.25rem;font-weight:600;color:#0f172a;margin:0 0 .25rem}
    .profile-info .subtle{color:#64748b;font-size:.875rem}

    .tags-container{display:flex;flex-wrap:wrap;gap:.5rem;margin:1rem 0}
    .tag{display:inline-flex;align-items:center;gap:.5rem;padding:.5rem 1rem;background:#f8fafc;border:1px solid #e5e7eb;border-radius:999px;font-size:.875rem;font-weight:500;color:#334155}
    .tag i{width:16px;height:16px}

    .stats-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin-top:1rem}
    @media (max-width:576px){ .stats-grid{grid-template-columns:1fr} }
    .stat-card{background:#f8fafc;border-radius:16px;padding:1rem;border:1px dashed #e5e7eb}
    .stat-label{font-size:.875rem;color:#64748b;margin-bottom:.25rem}
    .stat-value{font-size:1.125rem;font-weight:600;color:#0f172a}

    /* Progress */
    .progress-container{margin-top:1rem}
    .progress-bar-bg{height:8px;background:#eef2f7;border-radius:999px;overflow:hidden}
    .progress-bar-fill{height:100%;background:linear-gradient(90deg,var(--primary) 0%,var(--primary-2) 100%);transition:width .3s ease}
    .progress-info{display:flex;justify-content:space-between;margin-top:.5rem;font-size:.875rem;color:#64748b}

    /* Tabs (selaras report) */
    .tabs-container{display:flex;gap:.5rem;margin-bottom:1rem;border-bottom:1px solid #e2e8f0;padding-bottom:.75rem;flex-wrap:wrap}
    .tab-item{padding:.6rem 1rem;border-radius:12px;font-weight:500;color:#475569;transition:all .2s;display:inline-flex;align-items:center;gap:.5rem;text-decoration:none}
    .tab-item:hover{background:#f1f5f9}
    .tab-item.active{background:rgba(26,127,90,.10);color:#166534}
    .tab-item i{width:18px;height:18px}

    /* Info grid kanan */
    .info-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem}
    @media (max-width:768px){ .info-grid{grid-template-columns:1fr} }
    .info-card{background:#f8fafc;border-radius:16px;padding:1.25rem}
    .info-card-title{font-size:.875rem;color:#64748b;margin-bottom:.25rem}
    .info-card-value{font-size:1.125rem;font-weight:600;color:#0f172a;margin-bottom:.25rem}
    .info-card-meta{font-size:.875rem;color:#64748b}

    .actions-container{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:.75rem;margin-top:1rem;padding-top:1rem;border-top:1px solid #e2e8f0}

    /* Empty state */
    .empty-card{background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05)}

    /* Profile image placeholder */
    .profile-image-placeholder {
        width: 72px;
        height: 72px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #94a3b8;
        font-size: 1.5rem;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
@php
    $status = $contract->status ?? null;
    $statusClass = match(strtolower((string)$status)){
        'active','aktif' => 'active',
        'pending','menunggu' => 'pending',
        'expired','selesai','berakhir' => 'expired',
        default => 'muted'
    };
@endphp

<div class="report-container">
    <div class="page-header">
        <div class="header-content">
            <div>
                <h1 class="page-title">Kontrak Saya</h1>
                <p class="page-subtitle">Ringkasan status kontrak, masa berlaku, dan tindakan cepat</p>
            </div>

            @if(isset($contract) && $contract?->tanggal_keluar?->diffInDays(now()) <= 30)
                <button class="btn-add" data-bs-toggle="modal" data-bs-target="#extendModal">
                    <i data-feather="refresh-ccw"></i>
                    <span>Ajukan Perpanjangan</span>
                </button>
            @endif
        </div>
    </div>

    {{-- Body --}}
    @if($contract)
        <div class="grid-layout">
            <div class="section-card profile-card">
                <div class="profile-header">
                    @if($contract && $contract->ktp_image_url)
                        <img src="{{ $contract->ktp_image_url }}" alt="KTP {{ $contract->name }}" class="profile-image" onerror="handleImageError(this)">
                    @else
                        <div class="profile-image-placeholder">
                            <i data-feather="user"></i>
                        </div>
                    @endif
                    <div class="profile-info">
                        <h3>{{ $contract->name }}</h3>
                        <div class="subtle">{{ $contract->email }}</div>
                        <div class="subtle">{{ $contract->phone }}</div>
                    </div>
                </div>

                {{-- Add this temporarily below the profile header --}}
                @if(config('app.debug'))
                    <div class="d-none">
                        <p>KTP Image Path: {{ $contract->ktp_image }}</p>
                        <p>KTP URL: {{ $contract->ktp_image_url }}</p>
                        <p>Storage exists: {{ Storage::disk('public')->exists($contract->ktp_image) ? 'Yes' : 'No' }}</p>
                    </div>
                @endif

                <div class="tags-container">
                    <span class="tag"><i data-feather="home"></i> Kamar {{ $contract->kost->nomor_kamar }}</span>
                    <span class="tag"><i data-feather="credit-card"></i> Rp {{ number_format($contract->kost->harga,0,',','.') }}/bulan</span>
                    <span class="tag"><i data-feather="calendar"></i> {{ $contract->duration }} bulan</span>
                    <span class="tag badge {{ $statusClass }}">
                        <i data-feather="{{ $statusClass==='active'?'check-circle':($statusClass==='pending'?'clock':'x-circle') }}"></i>
                        {{ \Illuminate\Support\Str::headline($contract->status ?? 'Menunggu') }}
                    </span>
                </div>

                <div class="subtle">{{ $contract->alamat }}</div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Tanggal Masuk</div>
                        <div class="stat-value">{{ $contract->tanggal_masuk->translatedFormat('d M Y') }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Tanggal Keluar</div>
                        <div class="stat-value">{{ $contract->tanggal_keluar->translatedFormat('d M Y') }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Total Hari</div>
                        <div class="stat-value">{{ $totalDays }} hari</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Sisa Kontrak</div>
                        <div class="stat-value">{{ $remainingDays }} hari</div>
                    </div>
                </div>
            </div>

            {{-- Kanan: ringkasan & aksi --}}
            <div class="section-card content-card">
                <div class="tabs-container">
                    <span class="tab-item active"><i data-feather="file-text"></i> Ringkasan</span>
                    <a class="tab-item" href="{{ route('user.history.index') }}"><i data-feather="activity"></i> Riwayat Pembayaran</a>
                    <a class="tab-item" href="{{ route('user.contract') }}"><i data-feather="clipboard"></i> Detail Kontrak</a>
                </div>

                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-card-title">Tagihan Bulanan</div>
                        <div class="info-card-value">Rp {{ number_format($contract->kost->harga,0,',','.') }}</div>
                        <div class="info-card-meta">Termasuk listrik/air: <strong>{{ optional($contract->kost)->include_utility ? 'Ya' : 'Tidak' }}</strong></div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-title">Metode Pembayaran</div>
                        <div class="info-card-value">{{ $contract->payment_method ?? 'Transfer Bank' }}</div>
                        <div class="info-card-meta">Detail di menu Riwayat Pembayaran</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-title">Perpanjangan Mulai</div>
                        @php $mulai = $contract->tanggal_keluar->copy()->addDay(); @endphp
                        <div class="info-card-value">{{ $mulai->translatedFormat('d M Y') }}</div>
                        <div class="info-card-meta">Tanggal efektif setelah kontrak berakhir</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-title">Kontak Pengelola</div>
                        <div class="info-card-value">{{ $contract->manager_name ?? 'Admin Kost' }}</div>
                        <div class="info-card-meta">{{ $contract->manager_phone ?? '081238036180' }}</div>
                    </div>
                </div>

                <div class="actions-container">
                    <a href="{{ route('user.dashboard') }}" class="btn-secondary"><i data-feather="home"></i> Ke Dashboard</a>
                    <a href="{{ route('user.history.index') }}" class="btn-secondary"><i data-feather="credit-card"></i> Lihat Riwayat</a>
                    @if($contract->tanggal_keluar->diffInDays(now()) <= 30)
                        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#extendModal">
                            <i data-feather="refresh-ccw"></i> Ajukan Perpanjangan
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="empty-card">
            <div class="text-center p-5">
                <i data-feather="file-text" class="mb-3" style="width:48px;height:48px;color:#94a3b8"></i>
                <h3>Belum ada kontrak aktif</h3>
                <p class="text-muted">Silakan hubungi pengelola untuk memulai kontrak.</p>
                <a href="{{ route('user.dashboard') }}" class="btn-add">
                    <i data-feather="home"></i>
                    <span>Ke Dashboard</span>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

{{-- Modal Perpanjangan --}}
<div class="modal fade" id="extendModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('user.contract.extend') }}"
              enctype="multipart/form-data" id="extendForm">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Perpanjang Kontrak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Tambahkan input hidden untuk harga --}}
                <input type="hidden" id="roomPrice" value="{{ $contract->kost->harga }}">

                <div class="mb-3">
                    <label class="form-label">Durasi</label>
                    <select name="duration" class="form-select" id="duration" required>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}">{{ $i }} Bulan</option>
                        @endfor
                    </select>
                    <div class="form-text">Harga: Rp {{ number_format($contract->kost->harga,0,',','.') }} / bulan</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Pembayaran</label>
                    <div class="form-control bg-light" id="totalPayment">
                        Rp {{ number_format($contract->kost->harga,0,',','.') }}
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Bukti Pembayaran</label>
                    <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                </div>
                @php $mulai = $contract->tanggal_keluar->copy()->addDay(); @endphp
                <div class="alert alert-info">Perpanjangan mulai: <strong>{{ $mulai->translatedFormat('d F Y') }}</strong></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                <button class="btn-add" id="extSubmitBtn" type="submit">
                    <i data-feather="send"></i>
                    <span>Kirim Permohonan</span>
                </button>
            </div>
        </form>
    </div>
</div>
{{-- Modal Sukses --}}
<div class="modal fade" id="successExtendModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Berhasil!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" id="successExtendMsg"></div>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Periode Baru</span>
                        <strong id="successPeriod"></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Status</span>
                        <strong id="successStatus"></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>ID Permohonan</span>
                        <strong id="successOrderId"></strong>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@push('js')
<script>
  function initIcons(){
    if(!window.feather) return;
    document.querySelectorAll('[data-feather]').forEach(el=>{
      const name = el.getAttribute('data-feather');
      if(name && window.feather.icons[name]){
        el.outerHTML = window.feather.icons[name].toSvg({ 'stroke-width': 1.5, width: 16, height: 16, class: 'feather-16' });
      }
    });
  }
  function initTooltips(){
    const list = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    list.forEach(el => new bootstrap.Tooltip(el));
  }

  document.addEventListener('DOMContentLoaded', ()=>{
    initIcons();
    initTooltips();

    const form    = document.getElementById('extendForm');
    const btn     = document.getElementById('extSubmitBtn');
    const durSel  = document.getElementById('duration');
    const totalEl = document.getElementById('totalPayment');
    const errBox  = document.getElementById('extendErrors');
    const fileEl  = form ? form.querySelector('input[name="bukti_pembayaran"]') : null;

    // Ambil harga dari data attribute form
    const harga   = Number(form?.dataset.price || 0);
    const rupiah  = n => new Intl.NumberFormat('id-ID').format(n);

    // Update total ketika durasi berubah
    if(durSel && totalEl && harga){
        const updateTotal = () => {
            const duration = parseInt(durSel.value || 1);
            const total = harga * duration;
            totalEl.textContent = 'Rp ' + rupiah(total);
        };

        // Update saat pertama load
        updateTotal();

        // Update saat durasi berubah
        durSel.addEventListener('change', updateTotal);
    }

    if(!form || !btn) return;

    form.addEventListener('submit', async (e)=>{
      e.preventDefault();
      // reset errors
      errBox?.classList.add('d-none'); errBox && (errBox.innerHTML = '');
      fileEl?.classList.remove('is-invalid');
      durSel?.classList.remove('is-invalid');

      btn.disabled = true; btn.querySelector('span')?.textContent='Mengirim...';

      try{
        const fd = new FormData(form);
        // Tanda request JSON agar controller balas JSON
        const res = await fetch(form.action, {
          method: 'POST',
          body: fd,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          credentials: 'same-origin'
        });

        if(res.status === 422){
          const data = await res.json();
          const errors = data?.errors || {};
          let list = [];
          if(errors.duration){ durSel?.classList.add('is-invalid'); list.push(errors.duration.join('<br>')); }
          if(errors.bukti_pembayaran){ fileEl?.classList.add('is-invalid'); list.push(errors.bukti_pembayaran.join('<br>')); }
          if(errBox){ errBox.innerHTML = list.join('<hr>'); errBox.classList.remove('d-none'); }
          return;
        }

        if(!res.ok){
          throw new Error('Gagal mengirim permohonan. ('+res.status+')');
        }

        const data = await res.json();
        // Tutup modal form
        const extModalEl = document.getElementById('extendModal');
        const extModal   = extModalEl ? bootstrap.Modal.getOrCreateInstance(extModalEl) : null;
        extModal && extModal.hide();

        // Isi modal sukses
        document.getElementById('successExtendMsg').textContent = data?.message || 'Permohonan dikirim.';
        document.getElementById('successPeriod').textContent    = (data?.start && data?.end) ? (tglIndo(data.start)+' — '+tglIndo(data.end)) : '-';
        document.getElementById('successStatus').textContent    = data?.status || 'pending';
        document.getElementById('successOrderId').textContent   = data?.order_id || '-';

        // Tampilkan modal sukses
        const okModalEl = document.getElementById('successExtendModal');
        const okModal   = okModalEl ? new bootstrap.Modal(okModalEl) : null;
        okModal && okModal.show();

        // Reset form ringan (durasi balik ke 1, kosongkan file)
        if(durSel){ durSel.value = '1'; durSel.dispatchEvent(new Event('change')); }
        if(fileEl){ fileEl.value = ''; }

      } catch(err){
        // fallback alert
        if(errBox){ errBox.innerHTML = err.message || 'Terjadi kesalahan, coba lagi.'; errBox.classList.remove('d-none'); }
      } finally {
        btn.disabled = false; btn.querySelector('span')?.textContent='Kirim Permohonan';
      }
    });
  });

  function handleImageError(img) {
    console.error('Failed to load image:', img.src);
    const placeholder = document.createElement('div');
    placeholder.className = 'profile-image-placeholder';
    placeholder.innerHTML = '<i data-feather="user"></i>';
    img.parentNode.replaceChild(placeholder, img);
    feather.replace();
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format number to rupiah
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Get elements
    const durationSelect = document.getElementById('duration');
    const totalPayment = document.getElementById('totalPayment');
    const roomPrice = document.getElementById('roomPrice');

    // Calculate total when duration changes
    if (durationSelect && totalPayment && roomPrice) {
        const calculateTotal = () => {
            const duration = parseInt(durationSelect.value);
            const price = parseInt(roomPrice.value);
            const total = duration * price;
            totalPayment.textContent = `Rp ${formatRupiah(total)}`;
        }

        // Initial calculation
        calculateTotal();

        // Add event listener for duration changes
        durationSelect.addEventListener('change', calculateTotal);

        // Debug
        console.log('Initial price:', roomPrice.value);
        console.log('Initial duration:', durationSelect.value);
    } else {
        console.error('Some elements not found:', {
            durationSelect: !!durationSelect,
            totalPayment: !!totalPayment,
            roomPrice: !!roomPrice
        });
    }
});
</script>
@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ...existing code...

    const form = document.getElementById('extendForm');
    const btn = document.getElementById('extSubmitBtn');
    const successModal = new bootstrap.Modal(document.getElementById('successExtendModal'));

    if(form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            try {
                btn.disabled = true;
                btn.innerHTML = '<i data-feather="loader"></i> Mengirim...';

                const formData = new FormData(this);

                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Tutup modal form
                    const extendModal = bootstrap.Modal.getInstance(document.getElementById('extendModal'));
                    extendModal.hide();

                    // Update konten modal sukses
                    document.getElementById('successExtendMsg').textContent = data.message;
                    document.getElementById('successPeriod').textContent =
                        `${formatDate(data.start)} — ${formatDate(data.end)}`;
                    document.getElementById('successStatus').textContent = data.status;
                    document.getElementById('successOrderId').textContent = data.order_id;

                    // Tampilkan modal sukses
                    successModal.show();

                    // Reset form
                    form.reset();
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }

            } catch (error) {
                document.getElementById('extendErrors').textContent = error.message;
                document.getElementById('extendErrors').classList.remove('d-none');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i data-feather="send"></i> Kirim Permohonan';
                feather.replace();
            }
        });
    }
});

// Helper function untuk format tanggal
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
}
</script>
@endpush

