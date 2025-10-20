@extends('layouts.main')

@section('container')


<section class="hero-section d-flex align-items-center justify-content-center text-center text-white" id="home">
    <div class="container" data-aos="fade-up">
        <h1 class="display-3 fw-bold">Temukan Kenyamanan Anda di Kost Lolita</h1>
        <p class="lead my-4">Hunian eksklusif dengan fasilitas lengkap dan lokasi strategis di Pingiran Kota Ruteng.</p>
        <a href="#kamar" class="btn btn-primary btn-lg rounded-pill px-4">Lihat Pilihan Kamar</a>
    </div>
</section>


<section class="kamar-section section-padding" id="kamar">
    <div class="container">
        <div class="section-title text-center" data-aos="fade-up">
            <h2 class="display-5 fw-bold">Kamar Unggulan</h2>
            <p>Pilihan terbaik yang tersedia saat ini, siap untuk Anda tempati.</p>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
            @forelse($kosts_tersedia as $kost)
            <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="card kost-card h-100">

                    <div class="kost-card-img-container">
                        @php
                            $photos = $kost->foto;

                            $hasPhotos = !empty($photos) && is_array($photos) && count($photos) > 0;
                        @endphp

                        @if($hasPhotos)
                            <div id="carouselKamar{{ $kost->id }}" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">

                                <div class="carousel-indicators">
                                    @foreach ($photos as $index => $photo)
                                        <button type="button" data-bs-target="#carouselKamar{{ $kost->id }}" data-bs-slide-to="{{ $index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>


                                <div class="carousel-inner">
                                    @foreach ($photos as $photo)
                                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">


                                            <img src="{{ asset('storage/' . $photo) }}"
                                                 class="d-block w-100"
                                                 alt="Foto Kamar {{ $kost->nomor_kamar }}"
                                                 onerror="this.src='{{ asset('img/default-room.jpg') }}'">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <img src="{{ asset('img/default-room.jpg') }}"
                                 class="card-img-top"
                                 alt="Default Room">
                        @endif

                        @if($kost->status === 'Kosong')
                            <div class="status-badge">Tersedia</div>
                        @endif
                        <div class="rating-badge">
                            <i class="fas fa-star me-1"></i>{{ number_format($kost->rating ?? 4.9, 1) }}
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <div class="info-top">
                            <div>
                                <h5 class="card-title">Kamar {{ $kost->nomor_kamar }}</h5>
                                <div class="card-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $kost->alamat ?? 'Tenda, Kec. Langke Rembong.' }}</span>
                                </div>
                            </div>
                            <div class="price-pill">
                                Rp {{ number_format($kost->harga, 0, ',', '.') }}
                                <span class="unit">/bulan</span>
                            </div>
                        </div>


                        <div class="amenities">
                            <div class="amenities-item">
                                <i class="fas fa-bed"></i> 1 Tempat Tidur
                            </div>
                            <div class="amenities-item">
                                <i class="fas fa-bath"></i> 1 Kamar Mandi
                            </div>
                            <div class="amenities-item">
                                <i class="fas fa-wifi"></i> Wifi Gratis
                            </div>
                        </div>

                        <div class="mt-auto pt-4">
                            @auth
                                @if($kost->status == 'Kosong')
                                    <a href="{{ route('order.create', $kost->id) }}"
                                       class="btn btn-primary rounded-pill w-100">
                                        <i class="fas fa-key me-2"></i>Pesan Sekarang
                                    </a>
                                @else
                                    <button class="btn btn-secondary rounded-pill w-100" disabled>
                                        <i class="fas fa-lock me-2"></i>Sudah Terisi
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="btn btn-outline-primary rounded-pill w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login untuk Memesan
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    Maaf, saat ini tidak ada kamar tersedia.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>


<section class="fasilitas-section section-padding parallax-bg" id="fasilitas">
    <div class="container">

        <div class="section-title text-center text-white" data-aos="fade-up">
            <h2 class="display-5 fw-bold">Fasilitas Unggulan</h2>
            <p>Kami menyediakan semua yang Anda butuhkan untuk pengalaman tinggal yang tak terlupakan.</p>
        </div>
        <div class="row text-center text-white">
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="fasilitas-item">
                    <i class="fas fa-wifi fa-3x mb-3"></i>
                    <h5 class="fw-bold">WiFi Kecepatan Tinggi</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="fasilitas-item">
                    <i class="fas fa-car fa-3x mb-3"></i>
                    <h5 class="fw-bold">Area Parkir Luas</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="fasilitas-item">
                    <i class="fas fa-shield-alt fa-3x mb-3"></i>
                    <h5 class="fw-bold">Keamanan 24 Jam</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="fasilitas-item">
                    <i class="fas fa-square-parking fa-3x mb-3"></i>
                    <h5 class="fw-bold">Parkir Mobil</h5>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="tentang-section section-padding" id="tentang">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <img src="{{ asset('img/about.jpg') }}" class="img-fluid rounded shadow-lg" alt="Tentang Kost Lolita">
            </div>
            <div class="col-lg-6 ps-lg-5 mt-4 mt-lg-0" data-aos="fade-left">
                <div class="section-title">
                    <h2 class="display-5 fw-bold">Selamat Datang di Kost Lolita</h2>
                    <p>Lebih dari sekedar tempat tinggal, kami adalah rumah kedua bagi Anda.</p>
                </div>
                <p class="text-muted">Berdiri sejak tahun 2010, Kost Lolita berkomitmen untuk menyediakan hunian yang tidak hanya nyaman dan aman, tetapi juga mendukung produktivitas dan gaya hidup modern para penghuninya. Terletak di lokasi yang strategis, kami memberikan akses mudah ke berbagai fasilitas penting di Kota Malang.</p>
                <p class="text-muted">Kami percaya bahwa lingkungan yang baik adalah kunci untuk kesuksesan. Oleh karena itu, kami selalu berusaha menciptakan suasana yang kondusif, bersih, dan penuh kehangatan.</p>
            </div>
        </div>
    </div>
</section>

<section class="kontak-section section-padding bg-light" id="kontak">
    <div class="container">
        <div class="section-title text-center" data-aos="fade-up">
            <h2 class="display-5 fw-bold">Informasi & Kontak</h2>
            <p>
                Saat ini tersedia <strong class="text-primary">{{ $kosts_tersedia->count() }}</strong> dari total
                <strong class="text-primary">{{ $kosts_semua->count() }}</strong> kamar kami. Hubungi kami untuk detail lebih lanjut.
            </p>
        </div>
        <div class="row">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="kontak-info p-4 shadow-sm bg-white rounded h-100">
                    <h4 class="fw-bold mb-3">Hubungi Kami</h4>
                    <p><i class="fas fa-map-marker-alt me-2 text-primary"></i>Tenda, Kec. Langke Rembong.</p>
                    <p><i class="fas fa-phone me-2 text-primary"></i> 081 339 240 016 (Telepon)</p>
                    <p><i class="fas fa-envelope me-2 text-primary"></i> kostlolita@gmail.com (Email)</p>
                    <p><i class="fab fa-whatsapp me-2 text-primary"></i> 081 339 240 016 (WhatsApp)</p>
                </div>
            </div>
            <div class="col-lg-8 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="200">
                 <div class="daftar-kamar p-4 shadow-sm bg-white rounded h-100">
                    <h4 class="fw-bold mb-3">Daftar Lengkap Kamar</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Kamar</th>
                                    <th>Harga per Bulan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kosts_semua as $kost)
                                <tr>
                                    <td class="fw-bold">{{ $kost->nomor_kamar }}</td>
                                    <td>Rp. {{ number_format($kost->harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($kost->status == 'Kosong')
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger">Terisi</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</section>
@endsection
