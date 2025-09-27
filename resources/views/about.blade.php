{{-- GANTI SELURUH KODE SECTION ABOUT ANDA DENGAN INI --}}
@extends('layouts.app')
@section('content')
<section id="about" class="about">
    <div class="container">
        {{-- Header Section --}}
        <div class="section-header">
            <p class="section-tagline">Selamat Datang di Kost Lolita</p>
            <h2>Kenyamanan Modern di Jantung Kota <span>Ruteng</span></h2>
        </div>

        {{-- Main Grid Layout --}}
        <div class="about-grid">
            {{-- Kolom Gambar dengan Efek Tumpukan --}}
            <div class="about-image-stack">
                <div class="image-card-main">
                    <img src="img/about.jpg" alt="Kamar Kost Lolita yang nyaman">
                </div>
                <div class="image-card-secondary">
                    <img src="img/about-2.jpg" alt="Fasilitas modern di Kost Lolita">
                </div>
            </div>

            {{-- Kolom Konten Teks --}}
            <div class="about-content">
                <h3>Rumah Kedua Anda untuk Meraih Impian</h3>
                <p>
                    Kost Lolita bukan sekadar tempat tinggal, melainkan sebuah komunitas yang mendukung setiap langkah Anda di Ruteng. Kami merancang setiap sudut dengan detail untuk memastikan Anda mendapatkan kenyamanan, keamanan, dan inspirasi setiap hari.
                </p>

                {{-- Daftar Keunggulan dengan Ikon --}}
                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i data-feather="shield"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Keamanan 24 Jam</h4>
                            <p>Sistem CCTV dan penjaga keamanan untuk ketenangan Anda.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i data-feather="map-pin"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Lokasi Strategis</h4>
                            <p>Hanya beberapa menit dari Universitas St. Paulus Ruteng dan pusat kota.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i data-feather="wifi"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Fasilitas Modern</h4>
                            <p>Wi-Fi super cepat, Air, listrik dan perabotan lengkap di setiap kamar.</p>
                        </div>
                    </div>
                </div>

                {{-- Statistik Kunci --}}
                <div class="stats-container">
                    <div class="stat-item">
                        <h4>98%</h4>
                        <p>Tingkat Kepuasan</p>
                    </div>
                    <div class="stat-item">
                        <h4>100+</h4>
                        <p>Penghuni Bahagia</p>
                    </div>
                    <div class="stat-item">
                        <h4>5 min</h4>
                        <p>Ke Kampus</p>
                    </div>
                </div>

                <a href="{{ route('kamar') }}" class="btn-primary">Lihat Kamar Tersedia <i data-feather="arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>
@endsection
