@extends('layouts.order')
@section('title', 'Pesan Kamar')

@section('content')
<div class="order-container">
    <div class="back-button">
        <a href="{{ url('/') }}" class="btn-back">
            <i data-feather="arrow-left"></i>
            Kembali ke Beranda
        </a>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="room-preview-card sticky-top">
                <div class="room-image">
                    @if($kost->foto)
                        <img src="{{ asset('storage/'.$kost->foto) }}" alt="Kamar {{ $kost->nomor_kamar }}" class="room-img">
                    @endif
                    <div class="room-tag">Tersedia</div>
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
                    <div class="price-section">
                        <div class="price-label">Harga Sewa</div>
                        <div class="price">Rp {{ number_format($kost->harga, 0, ',', '.') }} <span>/bulan</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="order-form-card">
                <div class="form-header">
                    <h2>Data Pemesanan</h2>
                    <p>Silakan lengkapi data berikut dengan benar</p>
                </div>

                <form action="{{ route('order.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="needs-validation"
                      novalidate>
                    @csrf
                    <input type="hidden" name="kost_id" value="{{ $kost->id }}">

                    @if(session('error'))
                    <div class="alert alert-danger">
                        <i data-feather="alert-circle" class="me-2"></i>
                        {{ session('error') }}
                    </div>
                    @endif

                    <div class="form-section">
                        <h3>Data Pribadi</h3>
                        <div class="form-group">
                            <label class="required">Nama Lengkap</label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="required">Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   required>
                            <div class="form-text">
                                <i data-feather="info" class="feather-small me-1"></i>
                                Email ini akan digunakan untuk login akun Anda
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="required">No. WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="tel"
                                       name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}"
                                       placeholder="8xxxxxxxxxx"
                                       pattern="8[0-9]{8,11}"
                                       required>
                            </div>
                            <div class="form-text">
                                <i data-feather="info" class="feather-small me-1"></i>
                                Informasi akun akan dikirim ke WhatsApp Anda
                            </div>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="required">Alamat Lengkap</label>
                            <textarea name="alamat"
                                      class="form-control @error('alamat') is-invalid @enderror"
                                      rows="3"
                                      required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Informasi Sewa</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Lama Sewa</label>
                                    <select name="duration"
                                            class="form-select @error('duration') is-invalid @enderror"
                                            required>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('duration') == $i ? 'selected' : '' }}>
                                                {{ $i }} Bulan
                                            </option>
                                        @endfor
                                    </select>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Tanggal Masuk</label>
                                    <input type="date"
                                           name="tanggal_masuk"
                                           class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                           value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                                           min="{{ date('Y-m-d') }}"
                                           required>
                                    @error('tanggal_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="total-section mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="total-label">Total Pembayaran</div>
                                <div class="total-amount">
                                    Rp <span id="totalAmount">{{ number_format($kost->harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Dokumen & Pembayaran</h3>

                        <div class="form-group">
                            <label class="required">Foto KTP</label>
                            <div class="custom-file-upload">
                                <input type="file"
                                       name="ktp_image"
                                       id="ktp_image"
                                       accept="image/*"
                                       class="@error('ktp_image') is-invalid @enderror"
                                       required>
                                <label for="ktp_image">
                                    <i data-feather="upload-cloud"></i>
                                    <span>Pilih File KTP</span>
                                </label>
                            </div>
                            @error('ktp_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="required">Bukti Transfer</label>
                            <div class="payment-info mb-3">
                                <div class="bank-details">
                                    <h4>
                                        <i data-feather="credit-card" class="me-2"></i>
                                        Informasi Pembayaran
                                    </h4>
                                    <div class="bank-item">
                                        <div class="bank-name">Bank BCA</div>
                                        <div class="account-number">1234567890</div>
                                        <div class="account-name">a.n Kost Lolita</div>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-file-upload">
                                <input type="file"
                                       name="bukti_pembayaran"
                                       id="bukti_pembayaran"
                                       accept="image/*"
                                       class="@error('bukti_pembayaran') is-invalid @enderror"
                                       required>
                                <label for="bukti_pembayaran">
                                    <i data-feather="upload-cloud"></i>
                                    <span>Upload Bukti Transfer</span>
                                </label>
                            </div>
                            @error('bukti_pembayaran')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i data-feather="check-circle"></i>
                        <span>Kirim Pesanan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
.order-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.back-button {
    margin-bottom: 2rem;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #64748b;
    text-decoration: none;
    transition: color 0.2s;
}

.btn-back:hover {
    color: #1a7f5a;
}

.room-preview-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    margin-top: 1rem;
}

.room-image {
    position: relative;
}

.room-img {
    width: 100%;
    height: 300px;
    object-fit: cover;
}

.room-tag {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: #1a7f5a;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
}

.room-details {
    padding: 2rem;
}

.room-details h2 {
    color: #1e293b;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
}

.facilities h3 {
    color: #64748b;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.facility-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.facility-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #475569;
}

.price-section {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.price-label {
    color: #64748b;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.price {
    color: #1a7f5a;
    font-size: 1.875rem;
    font-weight: 600;
}

.price span {
    color: #64748b;
    font-size: 1rem;
    font-weight: normal;
}

.order-form-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}

.form-header {
    margin-bottom: 2rem;
}

.form-header h2 {
    color: #1e293b;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.form-header p {
    color: #64748b;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.form-section h3 {
    color: #1e293b;
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    color: #475569;
    margin-bottom: 0.5rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    transition: all 0.2s;
}

.form-control:focus {
    border-color: #1a7f5a;
    box-shadow: 0 0 0 3px rgba(26, 127, 90, 0.1);
}

.form-control.is-invalid {
    border-color: #dc2626;
}

.invalid-feedback {
    display: none;
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.was-validated .form-control:invalid ~ .invalid-feedback {
    display: block;
}

.custom-file-upload {
    margin-top: 0.5rem;
}

.custom-file-upload input {
    display: none;
}

.custom-file-upload label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: #f8fafc;
    border: 2px dashed #e2e8f0;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
}

.custom-file-upload label:hover {
    border-color: #1a7f5a;
    background: #f0fdf4;
}

.payment-info {
    background: #f8fafc;
    border-radius: 10px;
    padding: 1.5rem;
    margin-top: 0.5rem;
}

.bank-details {
    margin-bottom: 1.5rem;
}

.bank-details h4 {
    color: #1e293b;
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.account-number {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0.25rem 0;
}

.account-name {
    color: #64748b;
    font-size: 0.875rem;
}

.total-section {
    background: #f0fdf4;
    padding: 1rem;
    border-radius: 8px;
}

.total-label {
    color: #064e3b;
    font-size: 0.875rem;
}

.total-amount {
    color: #059669;
    font-size: 1.5rem;
    font-weight: 600;
}

.form-text {
    color: #64748b;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
}

.input-group-text {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #64748b;
}

.required:after {
    content: " *";
    color: #ef4444;
}

.feather-small {
    width: 14px;
    height: 14px;
}

.bank-item {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 0.5rem;
}

.bank-name {
    color: #64748b;
    font-size: 0.875rem;
}

.account-number {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0.25rem 0;
}

.account-name {
    color: #64748b;
    font-size: 0.875rem;
}

.total-section {
    background: #f0fdf4;
    padding: 1rem;
    border-radius: 8px;
}

.total-label {
    color: #064e3b;
    font-size: 0.875rem;
}

.total-amount {
    color: #059669;
    font-size: 1.5rem;
    font-weight: 600;
}

.form-text {
    color: #64748b;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
}

.input-group-text {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #64748b;
}
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace({
        'width': 18,
        'height': 18,
        'stroke-width': 2
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        this.classList.add('was-validated');
    });

    // File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const label = this.nextElementSibling;
            const span = label.querySelector('span');
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                span.textContent = fileName.length > 25
                    ? fileName.substring(0, 22) + '...'
                    : fileName;
            }
        });
    });

    // Calculate total amount
    const durationSelect = document.querySelector('select[name="duration"]');
    durationSelect.addEventListener('change', function() {
        const hargaPerBulan = {{ $kost->harga }};
        const durasi = parseInt(this.value);
        const total = hargaPerBulan * durasi;

        document.getElementById('totalAmount').textContent =
            new Intl.NumberFormat('id-ID').format(total);
    });

    // Phone number formatting
    const phoneInput = document.querySelector('input[name="phone"]');
    phoneInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.startsWith('0')) {
            this.value = this.value.substring(1);
        }
    });
});
</script>
@endpush
@endsection
