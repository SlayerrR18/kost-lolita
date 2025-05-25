@extends('layout.layout')
@section('styles')
<link rel="stylesheet" href="{{ asset('CSS/order.css') }}" />
@endsection
@section('content')
<div class="booking-container">
        <!-- Room Card Section -->
        <div class="room-card">
            <div>
                <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Kamar A" class="room-image">

                <h1 class="room-title">Kamar A</h1>

                <div class="facilities">
                    <h3>Fasilitas :</h3>
                    <div class="facility-item">
                        <svg class="facility-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Kamar mandi dalam
                    </div>
                    <div class="facility-item">
                        <svg class="facility-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        Lemari
                    </div>
                    <div class="facility-item">
                        <svg class="facility-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2v10h10V6H5z"/>
                        </svg>
                        Tempat Tidur
                    </div>
                </div>
            </div>

            <div class="price">
                <span class="price-currency">Rp</span> 650.000
                <div class="price-period">/Bulan</div>
            </div>
        </div>

        <!-- Booking Form Section -->
        <div class="booking-form">
            <h2 class="form-title">Masukan Data Diri</h2>

            <form action="/booking" method="POST" enctype="multipart/form-data">
                <!-- For Laravel Blade template, replace this line with: @csrf -->
                <input type="hidden" name="_token" value="">

                <div class="form-group">
                    <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-input"
                           placeholder="Masukkan nama lengkap anda" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="kamar_nomor">Kamar Nomor</label>
                        <select id="kamar_nomor" name="kamar_nomor" class="form-select" required>
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tanggal_mulai">Tanggal Mulai Kost</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-input" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="sewa">Sewa</label>
                        <select id="sewa" name="sewa" class="form-select" required>
                            <option value="per_bulan" selected>Per Bulan</option>
                            <option value="per_tahun">Per Tahun</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="bukti_transfer">Upload Bukti Transfer</label>
                        <div class="file-upload">
                            <input type="file" id="bukti_transfer" name="bukti_transfer"
                                   class="file-upload-input" accept="image/*" required>
                            <label for="bukti_transfer" class="file-upload-label">
                                <svg class="upload-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                                </svg>
                                Foto bukti transfer
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="sudah_menikah" selected>Sudah Menikah</option>
                        <option value="belum_menikah">Belum Menikah</option>
                    </select>
                </div>

                <div class="form-group" id="foto-upload-group">
                    <label class="form-label" for="foto_keluarga">Upload Foto Buku Nikah</label>
                    <div class="file-upload">
                        <input type="file" id="foto_keluarga" name="foto_keluarga[]"
                               class="file-upload-input" accept="image/*" multiple>
                        <label for="foto_keluarga" class="file-upload-label">
                            <svg class="upload-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                            </svg>
                            Foto Buku Nikah
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nomor_telefon">Nomor Telephone</label>
                    <input type="tel" id="nomor_telefon" name="nomor_telefon" class="form-input"
                           placeholder="085xxxxxxxx" required>
                </div>

                <button type="submit" class="submit-btn">Pesan Sekarang</button>
            </form>
        </div>
    </div>

    <script>
        // Status-based marriage certificate photo upload visibility
        function toggleFotoUpload() {
            const status = document.getElementById('status').value;
            const fotoUploadGroup = document.getElementById('foto-upload-group');
            const fotoInput = document.getElementById('foto_keluarga');

            if (status === 'sudah_menikah') {
                fotoUploadGroup.style.display = 'block';
                fotoInput.required = true;
            } else {
                fotoUploadGroup.style.display = 'none';
                fotoInput.required = false;
                fotoInput.value = ''; // Clear the file input
                // Reset the label
                const label = document.querySelector('label[for="foto_keluarga"]');
                label.innerHTML = `
                    <svg class="upload-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                    </svg>
                    Foto Buku Nikah
                `;
                label.style.color = '#718096';
                label.style.borderColor = '#cbd5e0';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleFotoUpload();
        });

        // Listen for status changes
        document.getElementById('status').addEventListener('change', toggleFotoUpload);

        // File upload preview for transfer proof
        document.getElementById('bukti_transfer').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const label = document.querySelector('label[for="bukti_transfer"]');

            if (file) {
                label.innerHTML = `
                    <svg class="upload-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ${file.name}
                `;
                label.style.color = '#62687B';
                label.style.borderColor = '#62687B';
            }
        });

        // File upload preview for marriage certificate photos
        document.getElementById('foto_keluarga').addEventListener('change', function(e) {
            const files = e.target.files;
            const label = document.querySelector('label[for="foto_keluarga"]');

            if (files.length > 0) {
                if (files.length === 1) {
                    label.innerHTML = `
                        <svg class="upload-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        ${files[0].name}
                    `;
                } else {
                    label.innerHTML = `
                        <svg class="upload-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        ${files.length} foto dipilih
                    `;
                }
                label.style.color = '#62687B';
                label.style.borderColor = '#62687B';
            }
        });

        // Phone number formatting
        document.getElementById('nomor_telefon').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0 && !value.startsWith('08')) {
                value = '08' + value;
            }
            if (value.length > 13) {
                value = value.slice(0, 13);
            }
            e.target.value = value;
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const phone = document.getElementById('nomor_telefon').value;
            const transferFile = document.getElementById('bukti_transfer').files[0];
            const status = document.getElementById('status').value;
            const fotoFile = document.getElementById('foto_keluarga').files;

            if (phone.length < 10) {
                e.preventDefault();
                alert('Nomor telefon minimal 10 digit');
                return;
            }

            if (!transferFile) {
                e.preventDefault();
                alert('Silakan upload bukti transfer');
                return;
            }

            if (status === 'sudah_menikah' && fotoFile.length === 0) {
                e.preventDefault();
                alert('Silakan upload foto buku nikah untuk status sudah menikah');
                return;
            }
        });
    </script>
@endsection
