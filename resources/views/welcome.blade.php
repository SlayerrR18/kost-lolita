<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
      <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kost Lolita</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Feather icon -->
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- Styel -->
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  </head>

    <body>
    <!-- Navbar Start-->
    <nav class="navbar">
        <a href="#" class="navbar-logo">
            <img src="{{ asset('img/Logo.png') }}" alt="Kost Lolita Logo" class="logo-img">
            Kost<span>Lolita</span>
        </a>
        <div class="isi-navbar">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#kamar">Kamar</a>
            <a href="#contact">Contact</a>
        </div>
        <div class="navbar-icon">
            <a href="{{ route('login') }}" class="login-link">
                <i data-feather="user"></i> Login
            </a>
            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Hero Start -->
    <section class="home" id="home">
        <main class="content">
            <h1>Hello!</h1>
            <p>Selamat datang di Kost Lolita<br>Tersedia kost pria bersih dan aman dengan fasilitas lengkap</p>
            <a href="#kamar" class="cta">Order Now!</a>
        </main>
    </section>
    <!-- Hero End -->

    <!-- About Start -->
    <section id="about" class="about">
        <h2>Tentang <span>Kami</span></h2>
        <div class="row">
            <div class="about-img">
                <img class="img-responsive" src="img/about.jpg" alt="Kamar tidur">
            </div>
            <div class="content">
                <h3>Mengapa memilih kami?</h3>
                <p>Dengan memilih kami, Anda akan mendapatkan tempat tinggal yang indah dan pastinya nyaman untuk aktivitas sehari-hari.</p>
                <p>Kami akan memberikan pelayanan terbaik untuk saudara sekalian dengan fasilitas modern dan lokasi strategis.</p>
                <p>Keamanan 24 jam, lingkungan bersih, dan suasana yang kondusif untuk istirahat maupun bekerja.</p>
            </div>
        </div>
    </section>
    <!-- About End -->

    <!-- Kamar Start -->
    <section id="kamar" class="rooms">
    <div class="container">
        <div class="section-header">
            <h2>Fasilitas <span>Kami</span></h2>
            <p>Pilihan kamar nyaman dengan berbagai fasilitas untuk kenyamanan Anda</p>
        </div>

        <div class="rooms-grid">
            @forelse($kosts as $kost)
            <div class="room-card">
                <div class="room-image">
                    @php
                        $fotos = is_array($kost->foto) ? $kost->foto : (empty($kost->foto) ? [] : [$kost->foto]);
                    @endphp

                    @if(count($fotos) > 0)
                        <div id="carouselKost{{ $kost->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($fotos as $i => $foto)
                                    <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/'.$foto) }}" class="d-block w-100" style="height:200px;object-fit:cover;border-radius:12px;" alt="Kamar {{ $kost->nomor_kamar }}">
                                    </div>
                                @endforeach
                            </div>
                            @if(count($fotos) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselKost{{ $kost->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselKost{{ $kost->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                            @endif
                        </div>
                    @else
                        <img src="{{ asset('img/default-room.jpg') }}" class="d-block w-100" style="height:200px;object-fit:cover;border-radius:12px;" alt="Default Room Image">
                    @endif
                    <div class="room-tag">{{ $kost->status }}</div>
                </div>

                <div class="room-content">
                    <h3>Kamar {{ $kost->nomor_kamar }}</h3>

                    <div class="room-features">
                        @foreach($kost->fasilitas as $fasilitas)
                            <div class="feature-item">
                                <i data-feather="check-circle"></i>
                                <span>{{ $fasilitas }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="room-price">
                        <div class="price-tag">
                            <span class="amount">Rp {{ number_format($kost->harga, 0, ',', '.') }}</span>
                            <span class="period">/ bulan</span>
                        </div>
                        @if($kost->isAvailable())
                        <a href="{{ route('order.create', $kost->id) }}" class="btn-book">
                            <i data-feather="key"></i>
                            Pesan Sekarang
                        </a>
                        @else
                        <button class="btn-book disabled" disabled>
                            <i data-feather="x-circle"></i>
                            Tidak Tersedia
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="no-rooms">
                <p>Belum ada kamar tersedia</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
    <!-- Kamar End -->

    <!-- Contact Start -->
    <section id="contact" class="contact">
        <h2>Kontak <span>Kami</span></h2>
        <p>Silahkan hubungi kami untuk informasi lebih lanjut</p>
        <div class="row">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3944.705885294979!2d120.46924977590868!3d-8.624208865505063!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2db3755f2ed80bf5%3A0x589b58910f455aac!2sKos%20Lolita!5e0!3m2!1sen!2sid!4v1702646203441!5m2!1sen!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe>
            <form action="">
                <div class="input-group">
                    <i data-feather="user"></i>
                    <input type="text" placeholder="Nama Lengkap" required>
                </div>
                <div class="input-group">
                    <i data-feather="mail"></i>
                    <input type="email" placeholder="Email Address" required>
                </div>
                <div class="input-group">
                    <i data-feather="phone"></i>
                    <input type="tel" placeholder="Nomor HP" required>
                </div>
                <button type="submit" class="btn">Kirim Pesan</button>
            </form>
        </div>
    </section>
    <!-- Contact End -->

    <!-- Footer Start -->
    <footer>
        <div class="social">
            <a href="tel:081238036180"><i data-feather="phone-call"></i> 081 238 036 180</a>
        </div>
        <div class="links">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#kamar">Kamar</a>
            <a href="#contact">Contact</a>
        </div>
        <div class="credit">
            <p>Created By Joy <span>Sakera</span> | &copy; 2023</p>
        </div>
    </footer>
    <!-- Footer End -->

    <!-- Feather Icons -->
    <script>
      feather.replace();
    </script>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
