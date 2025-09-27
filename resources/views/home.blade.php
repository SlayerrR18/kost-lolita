{{-- GANTI SELURUH KODE SECTION HOME ANDA DENGAN INI --}}
@extends('layouts.app')

@section('content')
<section class="home" id="home">
    <div class="container">
        <div class="home-grid">
            {{-- Kolom Kiri: Konten Teks --}}
            <main class="content">
                <span class="tagline">Selamat Datang di Kost Lolita, Ruteng</span>
                <h1>
                    Temukan <span class="highlight">Kenyamanan</span> dan <span class="highlight">Keamanan</span> di Rumah Keduanya Mahasiswa
                </h1>
                <p>
                    Berlokasi strategis dekat kampus ternama, Kost Lolita menawarkan fasilitas modern, lingkungan yang kondusif, dan keamanan 24 jam untuk mendukung kesuksesan studi Anda.
                </p>

                {{-- Keunggulan Utama --}}
                <div class="key-features">
                    <div class="feature">
                        <i data-feather="map-pin"></i>
                        <span>Lokasi Prima</span>
                    </div>
                    <div class="feature">
                        <i data-feather="shield"></i>
                        <span>Aman 24 Jam</span>
                    </div>
                    <div class="feature">
                        <i data-feather="wifi"></i>
                        <span>Fasilitas Lengkap</span>
                    </div>
                </div>

                <div class="cta-group">
                    <a href="{{ route('kamar') }}" class="cta-primary">Lihat Kamar <i data-feather="arrow-right"></i></a>
                    <a href="#about" class="cta-secondary">Tentang Kami</a>
                </div>
            </main>

            {{-- Kolom Kanan: Galeri Gambar Interaktif --}}
            <div class="hero-gallery">
                <div class="gallery-card card-1">
                    <img src="img/about.jpg" alt="Kamar Kost Tipe A">
                </div>
                <div class="gallery-card card-2">
                    <img src="img/bg-1.jpg" alt="Kamar Kost Tipe B">
                </div>
                <div class="gallery-card card-3">
                    <img src="img/bg-5.jpg" alt="Area Bersama Kost Lolita">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
