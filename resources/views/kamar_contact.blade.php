@extends('layouts.app')
@section('content')
<section id="kamar" class="rooms">
    <div class="container">
        <div class="section-header">
            <span class="section-tagline">Pilihan Terbaik di Ruteng</span>
            <h2>Temukan Kamar Impian Anda</h2>
            <p>Setiap kamar dirancang untuk memberikan kenyamanan maksimal dengan fasilitas modern yang akan menunjang aktivitas Anda sehari-hari.</p>
        </div>

        <div class="filter-bar">
            <button class="filter-btn active" data-filter="all">Semua</button>
            <button class="filter-btn" data-filter="Tersedia">Hanya yang Tersedia</button>
        </div>

        <div class="rooms-grid">
            @forelse($kosts as $kost)
            {{-- Menambahkan data-attribute untuk filtering JS --}}
            <div class="room-card" data-status="{{ $kost->status }}" data-type="{{-- $kost->tipe_kamar --}}">
                <div class="room-image">
                    @php
                        $fotos = is_array($kost->foto) ? $kost->foto : (empty($kost->foto) ? [] : [$kost->foto]);
                    @endphp

                    @if(count($fotos) > 0)
                        <div id="carouselKost{{ $kost->id }}" class="carousel slide" data-bs-ride="false">
                            {{-- Indicators dengan thumbnail --}}
                            <div class="carousel-indicators">
                                @foreach($fotos as $i => $foto)
                                <button type="button"
                                        data-bs-target="#carouselKost{{ $kost->id }}"
                                        data-bs-slide-to="{{ $i }}"
                                        class="{{ $i == 0 ? 'active' : '' }}"
                                        style="background-image: url('{{ asset('storage/'.$foto) }}');">
                                </button>
                                @endforeach
                            </div>

                            {{-- Carousel items --}}
                            <div class="carousel-inner">
                                @foreach($fotos as $i => $foto)
                                <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/'.$foto) }}"
                                         class="d-block w-100"
                                         alt="Kamar {{ $kost->nomor_kamar }}"
                                         style="height: 280px; object-fit: cover;">
                                </div>
                                @endforeach
                            </div>

                            {{-- Controls --}}
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
                        <i data-feather="{{ $kost->isAvailable() ? 'check' : 'x' }}"></i>
                        {{ $kost->status }}
                    </div>
                </div>

                <div class="room-content">
                    <div class="content-top">
                        <h3>Kamar No. {{ $kost->nomor_kamar }}</h3>
                        <p class="room-description">Ideal untuk mahasiswa yang mencari ketenangan dan fokus belajar.</p>
                    </div>

                    <div class="main-features">
                        <h4>Fasilitas Utama</h4>
                        <div class="features-grid">
                            @foreach($kost->fasilitas as $fasilitas)
                                @if($loop->index < 4) {{-- Tampilkan 4 fasilitas utama saja --}}
                                    <div class="feature-item">
                                        <i data-feather="check-circle"></i> <span>{{ $fasilitas }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="room-price">
                        <div class="price-tag">
                            <span class="amount">Rp {{ number_format($kost->harga, 0, ',', '.') }}</span>
                            <span class="period">/ bulan</span>
                        </div>
                        @if($kost->isAvailable())
                        <a href="{{ route('order.create', $kost->id) }}" class="btn-book">
                            Pesan Sekarang
                        </a>
                        @else
                        <button class="btn-book disabled" disabled>
                            Sudah Dipesan
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="no-rooms">
                <i data-feather="home"></i>
                <h3>Oops! Belum Ada Kamar</h3>
                <p>Saat ini belum ada kamar yang tersedia. Silakan cek kembali nanti.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
