@extends('layouts.app')
@section('content')
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
                                        <img src="{{ asset('storage/'.$foto) }}" class="d-block w-100" alt="Kamar {{ $kost->nomor_kamar }}">
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
                        <img src="{{ asset('img/default-room.jpg') }}" class="d-block w-100" alt="Default Room Image">
                    @endif
                    <div class="room-tag {{ $kost->isAvailable() ? 'status-available' : 'status-unavailable' }}">
                        {{ $kost->status }}
                    </div>
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
<!-- Contact Start -->
<section id="contact" class="contact">
    <h2>Kontak <span>Kami</span></h2>
    <p>Silahkan hubungi kami untuk informasi lebih lanjut</p>
    <div class="row">
        <iframe src="https://www.google.com/maps/embed?pb=..." allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe>
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
@endsection


<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
