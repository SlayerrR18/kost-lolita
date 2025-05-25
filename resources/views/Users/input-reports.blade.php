@extends('layout.layout')
@section('styles')
<link rel="stylesheet" href="{{ asset('CSS/input-reports.css') }}" />
@endsection
@section('content')

<div class="container">
        <h1 class="form-title">Sampaikan Keluhan Anda</h1>
        <p class="form-subtitle">Saran atau masalah yang di hadapin</p>

        <form action="#" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label class="form-label">Nama Pengirim</label>
                <input
                    type="text"
                    name="nama_pengirim"
                    class="input-field"
                    placeholder="Masukkan nama Anda"
                    required
                >
            </div>

            <div class="form-group">
                <textarea
                    class="textarea-field"
                    name="keluhan"
                    placeholder="Ada masalah pada keran kamar mandi"
                    required
                ></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Upload Foto</label>
                <div class="upload-section">
                    <label for="foto" class="upload-button">
                        <svg class="upload-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.2639 15.9375L12.5958 14.2834C12.267 13.9587 11.7424 13.9587 11.4137 14.2834L9.74556 15.9375" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M12 9V14.2273" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M20 12C20 16.4611 16.4611 20 12 20C7.53893 20 4 16.4611 4 12C4 7.53893 7.53893 4 12 4C16.4611 4 20 7.53893 20 12Z" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        Foto Jika Diperlukan
                    </label>
                    <input type="file" id="foto" name="foto" class="file-input" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Laporan</label>
                <input
                    type="date"
                    name="tanggal_laporan"
                    class="date-field"
                    required
                >
            </div>

            <button type="submit" class="submit-button">
                Kirim Sekarang
            </button>
        </form>
    </div>

    <script>
        // Add some interactivity
        document.querySelector('.file-input').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.querySelector('.upload-button').innerHTML = `
                    <svg class="upload-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    ${fileName}
                `;
            }
        });

        // Set today's date as default
        document.querySelector('.date-field').valueAsDate = new Date();
    </script>
@endsection
