@extends('layouts.order')
@section('title', 'Pesan Kamar')

@push('css')
<style>
    /* === Palet & Umum === */
    :root {
        --primary: #1a7f5a; --primary-light: #16c79a; --bg: #f8fafc; --ink: #1e293b;
        --muted: #64748b; --line: #e2e8f0; --card: #ffffff; --success: #16a34a; --danger: #ef4444;
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 10px 30px rgba(26, 127, 90, 0.1);
        --radius-md: 12px; --radius-lg: 24px;
    }
    body { font-family: 'Poppins', sans-serif; background-color: var(--bg); }

    /* Layout Utama & Tombol Kembali */
    .order-container { max-width: 1200px; margin: 2rem auto; padding: 1rem; }
    .btn-back { display: inline-flex; align-items: center; gap: 0.5rem; color: var(--muted); text-decoration: none; margin-bottom: 2rem; font-weight: 500; transition: color 0.3s ease; }
    .btn-back:hover { color: var(--primary); }

    /* Animasi masuk untuk kartu */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .room-preview-card, .order-form-card {
        animation: fadeInUp 0.6s ease-out backwards;
    }
    .order-form-card {
        animation-delay: 0.15s;
    }

    /* Kartu Ringkasan Kamar (Kiri) */
    .room-preview-card {
        background: var(--card);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        position: sticky;
        top: 2rem;
    }
    .room-image { border-radius: var(--radius-lg) var(--radius-lg) 0 0; overflow: hidden; height: 280px; }
    .room-image .carousel-item img { height: 280px; object-fit: cover; }
    .room-details { padding: 1.5rem; }
    .room-details h2 { font-size: 1.5rem; font-weight: 600; color: var(--ink); margin-bottom: 1rem; }
    .facilities h3 { font-size: 1rem; font-weight: 600; color: var(--muted); margin-bottom: 1rem; }
    .facility-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .facility-item { display: flex; align-items: center; gap: 0.5rem; color: var(--muted); }
    .facility-item i { color: var(--primary); width: 16px; }

    /* Kalkulator Total Dinamis */
    .dynamic-price-summary {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--line);
    }
    .price-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; color: var(--muted); }
    .price-row span:last-child { color: var(--ink); font-weight: 500; }
    .total-price-row {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px dashed var(--line);
    }
    .total-price-row .total-label { font-weight: 600; font-size: 1.1rem; }
    .total-price-row .total-amount { font-weight: 700; font-size: 1.5rem; color: var(--primary); }

    /* Kartu Form Pemesanan (Kanan) */
    .order-form-card { background: var(--card); border-radius: var(--radius-lg); padding: 2.5rem; box-shadow: var(--shadow-md); }
    .form-header { text-align: center; margin-bottom: 2.5rem; }
    .form-header h2 { font-size: 2rem; font-weight: 700; color: var(--ink); }
    .form-header p { color: var(--muted); }

    /* Desain Form Section & Steps */
    .form-step { border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.5rem; }
    .form-step-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
    .step-number { width: 40px; height: 40px; background: var(--primary); color: white; border-radius: 50%; display: grid; place-items: center; font-weight: 700; flex-shrink: 0; }
    .step-title h3 { font-size: 1.2rem; color: var(--ink); margin: 0; }
    .step-title p { font-size: 0.9rem; color: var(--muted); margin: 0; }

    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; color: var(--ink-light); margin-bottom: 0.5rem; font-weight: 500; }
    .form-control, .form-select { border: 1px solid var(--line); border-radius: var(--radius-md); padding: 0.75rem 1rem; transition: all 0.3s ease; width: 100%; }
    .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(26,127,90,0.1); }
    .was-validated .form-control:invalid, .was-validated .form-select:invalid { border-color: var(--danger); }
    .required:after { content: " *"; color: var(--danger); }
    .form-text { color: var(--muted); font-size: 0.8rem; margin-top: 0.5rem; display: flex; align-items: center; }
    .feather-small { width: 14px; height: 14px; }

    /* Informasi Pembayaran */
    .payment-info-box { background: var(--primary-bg); border: 1px dashed var(--primary-soft); border-radius: var(--radius-md); padding: 1rem; }
    .bank-item { display: flex; justify-content: space-between; align-items: center; }
    .bank-details .bank-name { font-size: 0.9rem; color: var(--muted); }
    .bank-details .account-number { font-weight: 600; font-size: 1.1rem; }
    .btn-copy { background: none; border: 1px solid var(--line); border-radius: 8px; color: var(--muted); cursor: pointer; padding: 0.5rem; transition: all 0.2s ease; }
    .btn-copy:hover { background: var(--primary-soft); color: var(--primary); }

    /* File Upload */
    .file-upload-wrapper { border: 2px dashed var(--line); border-radius: var(--radius-md); padding: 1.5rem; text-align: center; transition: all 0.3s ease; }
    .file-upload-wrapper:hover { border-color: var(--primary); background-color: var(--primary-bg); }
    .file-upload-wrapper input[type="file"] { display: none; }
    .file-upload-label { cursor: pointer; color: var(--muted); }
    .file-upload-label i { width: 32px; height: 32px; margin-bottom: 0.5rem; }
    .file-upload-label span { display: block; font-weight: 500; }
    .file-upload-wrapper.has-file { border-style: solid; border-color: var(--success); background-color: var(--primary-bg); }
    .file-upload-wrapper.has-file .file-name-display { font-size: 0.85rem; color: var(--primary); margin-top: 0.5rem; font-weight: 600; }

    /* Tombol Submit */
    .btn-submit {
        display: flex; align-items: center; justify-content: center; width: 100%;
        padding: 0.875rem; border-radius: var(--radius-md);
        background: var(--primary); color: white; font-size: 1.1rem; font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-submit:hover { background: #15664a; transform: translateY(-2px); box-shadow: var(--shadow-lg); }
    .btn-submit:disabled { background: var(--muted); cursor: not-allowed; transform: none; box-shadow: none; }
    .spinner-border-sm { width: 1rem; height: 1rem; border-width: .2em; }

    @media (max-width: 992px) {
        .room-preview-card { position: static; margin-bottom: 2rem; }
    }
</style>
@endpush

@section('content')
<div class="order-container">
    <div class="back-button">
        <a href="{{ url('/') }}" class="btn-back">
            <i data-feather="arrow-left"></i>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="room-preview-card">
                <div class="room-image">
                    @php
                        $fotos = is_array($kost->foto) ? $kost->foto : (empty($kost->foto) ? [] : [$kost->foto]);
                    @endphp
                    @if(count($fotos) > 0)
                        <div id="carouselOrderKost{{ $kost->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($fotos as $i => $foto)
                                <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/'.$foto) }}" class="d-block w-100" alt="Kamar {{ $kost->nomor_kamar }}">
                                </div>
                                @endforeach
                            </div>
                            @if(count($fotos) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselOrderKost{{ $kost->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselOrderKost{{ $kost->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                            @endif
                        </div>
                    @else
                        <img src="{{ asset('img/default-room.jpg') }}" alt="Kamar Default">
                    @endif
                </div>
                <div class="room-details">
                    <h2>Kamar {{ $kost->nomor_kamar }}</h2>
                    <div class="facilities">
                        <h3>Fasilitas Kamar:</h3>
                        <div class="facility-grid">
                            @foreach($kost->fasilitas as $fasilitas)
                            <div class="facility-item">
                                <i data-feather="check-circle"></i>
                                <span>{{ $fasilitas }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="dynamic-price-summary">
                        <div class="price-row">
                            <span>Harga per bulan</span>
                            <span id="hargaPerBulan">Rp {{ number_format($kost->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="price-row">
                            <span>Lama sewa</span>
                            <span id="lamaSewaText">1 Bulan</span>
                        </div>
                        <div class="price-row">
                            <span>Tanggal Masuk</span>
                            <span id="tanggalMasukText">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="price-row total-price-row">
                            <span class="total-label">Total Pembayaran</span>
                            <span class="total-amount" id="totalAmount">Rp {{ number_format($kost->harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="order-form-card">
                <div class="form-header">
                    <h2>Formulir Pemesanan</h2>
                    <p>Lengkapi data di bawah ini untuk menyelesaikan pesanan Anda.</p>
                </div>

                <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="kost_id" value="{{ $kost->id }}">
                    @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center"><i data-feather="alert-circle" class="me-2"></i>{{ session('error') }}</div>
                    @endif

                    <div class="form-step">
                        <div class="form-step-header">
                            <div class="step-number">1</div>
                            <div class="step-title"><h3>Data Pribadi</h3><p>Informasi penyewa sesuai KTP.</p></div>
                        </div>
                        <div class="form-group"><label for="name" class="required">Nama Lengkap</label><input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        <div class="form-group"><label for="email" class="required">Email</label><input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required><div class="form-text"><i data-feather="info" class="feather-small me-1"></i>Email ini akan digunakan untuk login.</div>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        <div class="form-group"><label for="phone" class="required">No. WhatsApp</label><div class="input-group"><span class="input-group-text">+62</span><input type="tel" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="8xxxxxxxxxx" required></div>@error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>

                        <div class="form-group">
                            <label for="alamat" class="required">Alamat Lengkap (Sesuai KTP)</label>
                            <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat') }}</textarea>
                            <div class="form-text"><i data-feather="info" class="feather-small me-1"></i>Pastikan alamat diisi lengkap untuk keperluan administrasi.</div>
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>

                    <div class="form-step">
                       <div class="form-step-header">
                           <div class="step-number">2</div>
                           <div class="step-title"><h3>Detail Sewa</h3><p>Tentukan lama sewa dan tanggal masuk.</p></div>
                       </div>
                       <div class="row">
                           <div class="col-md-6 form-group"><label for="duration" class="required">Lama Sewa</label><select id="duration" name="duration" class="form-select @error('duration') is-invalid @enderror" required>@for($i = 1; $i <= 12; $i++)<option value="{{ $i }}" {{ old('duration') == $i ? 'selected' : '' }}>{{ $i }} Bulan</option>@endfor</select></div>
                           <div class="col-md-6 form-group"><label for="tanggal_masuk" class="required">Tanggal Masuk</label><input type="date" id="tanggal_masuk" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required></div>
                       </div>
                    </div>

                    <div class="form-step">
                        <div class="form-step-header">
                           <div class="step-number">3</div>
                           <div class="step-title"><h3>Pembayaran & Dokumen</h3><p>Silakan transfer dan unggah bukti pembayaran.</p></div>
                        </div>
                        <div class="payment-info-box mb-3">
                           <div class="bank-item">
                               <div class="bank-details"><div class="bank-name">Bank BCA</div><div class="account-number" id="accountNumber">1234567890 (a.n Kost Lolita)</div></div>
                               <button type="button" class="btn-copy" onclick="copyAccountNumber()" title="Salin No. Rekening"><i data-feather="copy" id="copyIcon"></i></button>
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="required">Foto KTP</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="ktp_image" id="ktp_image" accept="image/*" class="@error('ktp_image') is-invalid @enderror" required>
                                    <label for="ktp_image" class="file-upload-label">
                                        <i data-feather="upload-cloud"></i>
                                        <span class="file-name-display">Pilih atau jatuhkan file</span>
                                    </label>
                                </div>
                                @error('ktp_image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                             <div class="col-md-6 form-group">
                                <label class="required">Bukti Transfer</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*" class="@error('bukti_pembayaran') is-invalid @enderror" required>
                                    <label for="bukti_pembayaran" class="file-upload-label">
                                        <i data-feather="upload-cloud"></i>
                                        <span class="file-name-display">Pilih atau jatuhkan file</span>
                                    </label>
                                </div>
                                @error('bukti_pembayaran')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span>Kirim & Selesaikan Pesanan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();

    const form = document.querySelector('form.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Memproses...`;
        }
        form.classList.add('was-validated');
    });

    function setupFileUpload(inputId) {
        const input = document.getElementById(inputId);
        const wrapper = input.parentElement;
        const display = wrapper.querySelector('.file-name-display');

        if (input) {
            input.addEventListener('change', function() {
                if (this.files.length > 0) {
                    display.textContent = this.files[0].name;
                    wrapper.classList.add('has-file');
                } else {
                    display.textContent = 'Pilih atau jatuhkan file';
                    wrapper.classList.remove('has-file');
                }
            });
        }
    }
    setupFileUpload('ktp_image');
    setupFileUpload('bukti_pembayaran');

    const durationSelect = document.getElementById('duration');
    const tanggalMasukInput = document.getElementById('tanggal_masuk');
    const hargaPerBulan = {{ $kost->harga }};
    const totalAmountEl = document.getElementById('totalAmount');
    const lamaSewaTextEl = document.getElementById('lamaSewaText');
    const tanggalMasukTextEl = document.getElementById('tanggalMasukText');

    function updateTotal() {
        const durasi = parseInt(durationSelect.value);
        const total = hargaPerBulan * durasi;
        totalAmountEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        lamaSewaTextEl.textContent = durasi + (durasi > 1 ? ' Bulan' : ' Bulan');

        const tanggalValue = tanggalMasukInput.value;
        if (tanggalValue) {
            const date = new Date(tanggalValue);
            // Menambahkan timezone offset agar tanggal tidak mundur
            const userTimezoneOffset = date.getTimezoneOffset() * 60000;
            const correctedDate = new Date(date.getTime() + userTimezoneOffset);

            tanggalMasukTextEl.textContent = correctedDate.toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });
        }
    }

    if(durationSelect && tanggalMasukInput) {
        durationSelect.addEventListener('change', updateTotal);
        tanggalMasukInput.addEventListener('change', updateTotal);
        updateTotal();
    }

    window.copyAccountNumber = function() {
        const accountNumberText = document.getElementById('accountNumber').textContent;
        const accountNumber = accountNumberText.match(/\d+/)[0];
        navigator.clipboard.writeText(accountNumber).then(() => {
            const copyIcon = document.getElementById('copyIcon');
            copyIcon.outerHTML = `<i data-feather="check" id="copyIcon" style="color: var(--success);"></i>`;
            feather.replace();
            setTimeout(() => {
                document.getElementById('copyIcon').outerHTML = `<i data-feather="copy" id="copyIcon"></i>`;
                feather.replace();
            }, 2000);
        });
    }
});
</script>
@endpush
