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

                    @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                    @endif

                    <input type="hidden" name="kost_id" value="{{ $kost->id }}">

                    <div class="form-section">
                        <h3>Data Pribadi</h3>
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                            <small class="form-text">Email ini akan digunakan untuk login akun Anda</small>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>No. Telepon</label>
                                    <input type="tel" name="phone" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Dokumen</h3>
                        <div class="form-group">
                            <label>Foto KTP</label>
                            <div class="custom-file-upload">
                                <input type="file" name="ktp_image" accept="image/*" required id="ktp_image">
                                <label for="ktp_image">
                                    <i data-feather="upload"></i>
                                    <span>Pilih File KTP</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Bukti Pembayaran</label>
                            <div class="payment-info">
                                <div class="bank-details">
                                    <h4>Informasi Rekening:</h4>
                                    <p>Bank BCA</p>
                                    <p class="account-number">1234567890</p>
                                    <p>a.n Kost Lolita</p>
                                </div>
                                <div class="custom-file-upload">
                                    <input type="file" name="bukti_pembayaran" accept="image/*" required id="payment_proof">
                                    <label for="payment_proof">
                                        <i data-feather="upload"></i>
                                        <span>Upload Bukti Transfer</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duration" class="form-label">Lama Sewa (Bulan)</label>
                                <select class="form-select @error('duration') is-invalid @enderror"
                                        id="duration" name="duration" required>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }} Bulan</option>
                                    @endfor
                                </select>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                                <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                       id="tanggal_masuk" name="tanggal_masuk"
                                       min="{{ date('Y-m-d') }}" required>
                                @error('tanggal_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Pembayaran</label>
                        <div class="form-control bg-light">
                            Rp <span id="totalPembayaran">{{ number_format($kost->harga, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i data-feather="check-circle"></i>
                        Kirim Pesanan
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
    margin: 0.5rem 0;
}

.btn-submit {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background: #1a7f5a;
    color: white;
    border: none;
    padding: 1rem;
    border-radius: 10px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-submit:hover {
    background: #15664a;
    transform: translateY(-1px);
}

.alert {
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.alert-danger {
    background: #fef2f2;
    border: 1px solid #fee2e2;
    color: #dc2626;
}

@media (max-width: 768px) {
    .order-container {
        padding: 1rem;
    }

    .room-preview-card {
        margin-bottom: 2rem;
    }

    .facility-grid {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    }
}
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();

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
            if (this.files.length > 0) {
                label.querySelector('span').textContent = this.files[0].name;
            }
        });
    });

    document.getElementById('duration').addEventListener('change', function() {
        const hargaPerBulan = {{ $kost->harga }};
        const durasi = this.value;
        const total = hargaPerBulan * durasi;

        document.getElementById('totalPembayaran').textContent =
            new Intl.NumberFormat('id-ID').format(total);
    });
});
</script>
@endpush
@endsection
