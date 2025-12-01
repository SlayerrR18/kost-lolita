<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kost Lolita - Hunian Nyaman & Modern</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* --- KONFIGURASI WARNA --- */
        :root {
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Inter', sans-serif;
            --color-primary: #222831;
            --color-secondary: #DFD0B8;
            --color-accent: #948979;
            --color-bg-light: #f9f7f4;
        }

        /* Override Tailwind Colors */
        .bg-primary { background-color: var(--color-primary) !important; }
        .bg-secondary { background-color: var(--color-secondary) !important; }
        .bg-accent { background-color: var(--color-accent) !important; }
        .text-primary { color: var(--color-primary) !important; }
        .text-secondary { color: var(--color-secondary) !important; }
        .text-accent { color: var(--color-accent) !important; }
        .text-white { color: #ffffff !important; }
        .border-primary { border-color: var(--color-primary) !important; }
        .hover\:bg-accent:hover { background-color: var(--color-accent) !important; }

        body {
            font-family: var(--font-sans);
            color: var(--color-primary);
            background-color: #ffffff;
        }

        h1, h2, h3, h4, h5, h6, .font-serif {
            font-family: var(--font-serif);
        }

        /* --- 1. EFEK NAVBAR HOVER (Garis Bawah Berjalan) --- */
        .nav-link {
            position: relative;
            padding-bottom: 5px;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--color-primary);
            transition: width 0.3s ease-in-out;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link.active {
            color: var(--color-primary);
            font-weight: 600;
        }

        /* --- 2. SCROLL REVEAL ANIMATION (Muncul dari bawah) --- */
        .reveal-element {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal-element.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Delay bertingkat untuk elemen dalam grup */
        .reveal-delay-100 { transition-delay: 0.1s; }
        .reveal-delay-200 { transition-delay: 0.2s; }
        .reveal-delay-300 { transition-delay: 0.3s; }

        /* --- 3. CUSTOM UTILITIES --- */
        .btn-primary {
            @apply bg-primary text-white px-8 py-3 rounded-full flex items-center gap-2 transition-all duration-300 hover:shadow-lg hover:-translate-y-1;
        }
        .btn-secondary {
            @apply bg-secondary text-primary px-8 py-3 rounded-full flex items-center gap-2 transition-all duration-300 hover:shadow-lg hover:-translate-y-1;
        }
        .rounded-custom-hero { border-radius: 0 0 0 150px; }
        .rounded-custom-about { border-radius: 100px 0 0 0; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased selection:bg-secondary selection:text-primary">

    @auth
        @include('layouts.welcome-navbar')
    @endauth

    @guest
        <header x-data="{ isScrolled: false }"
                @scroll.window="isScrolled = (window.pageYOffset > 20)"
                :class="isScrolled ? 'bg-white/95 backdrop-blur-md shadow-md py-4' : 'bg-transparent py-6'"
                class="fixed top-0 w-full z-50 transition-all duration-300 ease-in-out">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <a href="/" class="flex items-center gap-3 group">
                        <img src="{{ asset('img/Logo.png') }}" alt="Kost Lolita" class="h-10 transition-transform duration-500 group-hover:rotate-6">
                        <span class="font-serif text-2xl font-bold text-primary">Kost <span class="text-accent">Lolita</span></span>
                    </a>

                    <nav class="hidden md:flex items-center gap-8 font-medium text-gray-500" id="desktop-nav">
                        <a href="#hero" class="nav-link hover:text-primary">Home</a>
                        <a href="#about" class="nav-link hover:text-primary">Tentang Kami</a>
                        <a href="#features" class="nav-link hover:text-primary">Fitur</a>
                        <a href="#rooms" class="nav-link hover:text-primary">Kamar</a>
                        <a href="#contact" class="nav-link hover:text-primary">Kontak</a>

                        <a href="{{ route('login') }}"
                        class="ml-4 bg-secondary text-primary px-6 py-2 rounded-full hover:bg-opacity-80 transition hover:shadow-md transform hover:scale-105 active:scale-95 duration-200">
                            Masuk
                        </a>
                    </nav>

                    <div class="md:hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 rounded-md text-primary hover:bg-gray-100 text-xl transition-transform active:scale-90">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             @click.away="open = false"
                             class="absolute top-full left-0 w-full bg-white border-t border-gray-100 shadow-xl py-4 px-4 flex flex-col gap-4">
                            <a href="#hero" @click="open=false" class="block text-primary hover:text-accent font-medium">Home</a>
                            <a href="#about" @click="open=false" class="block text-primary hover:text-accent font-medium">Tentang Kami</a>
                            <a href="#features" @click="open=false" class="block text-primary hover:text-accent font-medium">Fitur</a>
                            <a href="#rooms" @click="open=false" class="block text-primary hover:text-accent font-medium">Kamar</a>
                            <a href="#contact" @click="open=false" class="block text-primary hover:text-accent font-medium">Kontak</a>
                            <a href="{{ route('login') }}" class="block text-center bg-secondary text-primary py-3 rounded-full font-bold">Masuk</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    @endguest

    <section id="hero" class="relative bg-bg-light pt-32 pb-24 overflow-hidden min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8 max-w-lg reveal-element">
                    <div class="inline-block px-4 py-1.5 rounded-full border border-accent/30 bg-white/50 backdrop-blur-sm text-accent text-sm font-semibold tracking-wider uppercase mb-2">
                        Selamat Datang Di Kost Lolita
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-bold leading-tight text-primary font-serif">
                        Temukan <span class="text-accent italic relative">
                            Kenyamanan
                            <svg class="absolute w-full h-3 -bottom-1 left-0 text-secondary opacity-60" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="8" fill="none" /></svg>
                        </span> Hunian Impian Anda
                    </h1>
                    <p class="text-gray-600 text-lg leading-relaxed reveal-element reveal-delay-100">
                        Kost modern dengan fasilitas lengkap, lingkungan aman, dan lokasi strategis. Kami menciptakan suasana rumah yang sesungguhnya untuk Anda.
                    </p>
                    <div class="flex flex-wrap gap-4 reveal-element reveal-delay-200">
                        <a href="#rooms" class="btn-primary group">
                            Lihat Kamar
                            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="{{ route('register') }}" class="btn-secondary">Daftar Akun</a>
                    </div>

                    <div class="mt-8 pt-8 border-t border-gray-200/60 flex gap-12 reveal-element reveal-delay-300">
                         <div>
                            <span class="block text-4xl font-serif font-bold text-primary">{{ $totalRooms ?? '20' }}+</span>
                            <div class="flex items-center gap-2 text-gray-500 text-sm tracking-wide uppercase mt-1">
                                <i class="fa-solid fa-bed text-accent"></i> Total Kamar
                            </div>
                        </div>
                        <div>
                            <span class="block text-4xl font-serif font-bold text-accent">{{ $availableRooms ?? '5' }}</span>
                            <div class="flex items-center gap-2 text-gray-500 text-sm tracking-wide uppercase mt-1">
                                <i class="fa-solid fa-door-open text-primary"></i> Tersedia
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative h-[500px] lg:h-[650px] w-full reveal-element reveal-delay-200">
                     <img src="{{ asset('img/home.jpg') }}" alt="Interior Kost Lolita" class="absolute inset-0 w-full h-full object-cover rounded-custom-hero shadow-2xl transition-transform hover:scale-[1.02] duration-1000">
                     <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-secondary/30 rounded-full blur-3xl -z-10 animate-pulse"></div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="relative h-[500px] w-full order-last lg:order-first reveal-element">
                    <img src="{{ asset('img/about.jpg') }}" alt="Suasana Kost Lolita" class="absolute inset-0 w-full h-full object-cover rounded-custom-about shadow-2xl">
                    <div class="absolute top-10 right-10 w-24 h-24 border-4 border-secondary rounded-full opacity-50"></div>
                </div>

                <div class="space-y-8 reveal-element reveal-delay-100">
                    <span class="text-accent font-bold tracking-widest uppercase text-sm">Tentang Kami</span>
                    <h2 class="text-4xl lg:text-5xl font-bold text-primary leading-tight font-serif">
                        Kami Menciptakan Lebih Dari Sekadar Tempat Tidur
                    </h2>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        Di Kost Lolita, kami percaya bahwa tempat tinggal adalah fondasi kesuksesan. Kami berkomitmen menyediakan lingkungan yang bersih, inspiratif, dan hangat. Manajemen kami yang responsif siap membantu kebutuhan harian Anda.
                    </p>

                    <ul class="space-y-4 mt-4">
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-secondary text-xl"></i>
                            <span class="font-medium text-primary">Lingkungan Bersih & Terawat</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-secondary text-xl"></i>
                            <span class="font-medium text-primary">Komunitas Positif</span>
                        </li>
                    </ul>

                    <div class="flex items-center gap-6 pt-6 border-t border-gray-100 mt-8">
                        <div class="bg-secondary w-14 h-14 flex items-center justify-center rounded-full text-primary shadow-lg text-xl animate-bounce">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <p class="font-bold text-xl text-primary font-serif">081 339 240 016</p>
                            <p class="text-gray-600 text-sm">Hubungi Layanan Pelanggan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-bg-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 reveal-element">
                <span class="text-accent font-bold tracking-widest uppercase text-sm">Fasilitas</span>
                <h2 class="text-4xl font-bold font-serif text-primary mt-2">Kenapa Memilih Kami?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 group reveal-element reveal-delay-100 border border-gray-100">
                    <div class="w-16 h-16 bg-secondary/20 rounded-2xl flex items-center justify-center text-accent text-3xl mb-6 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h3 class="text-2xl font-bold font-serif mb-3">Fasilitas Premium</h3>
                    <p class="text-gray-600 leading-relaxed">Nikmati Kamar Luas, Wi-Fi berkecepatan tinggi, Tempat Parkir, dan area komunal yang nyaman.</p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 group reveal-element reveal-delay-200 border border-gray-100">
                     <div class="w-16 h-16 bg-secondary/20 rounded-2xl flex items-center justify-center text-accent text-3xl mb-6 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <h3 class="text-2xl font-bold font-serif mb-3">Lokasi Strategis</h3>
                    <p class="text-gray-600 leading-relaxed">Hanya 5 menit dari kampus utama, dekat pusat perbelanjaan, kuliner, dan akses transportasi.</p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 group reveal-element reveal-delay-300 border border-gray-100">
                     <div class="w-16 h-16 bg-secondary/20 rounded-2xl flex items-center justify-center text-accent text-3xl mb-6 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-2xl font-bold font-serif mb-3">Keamanan 24/7</h3>
                    <p class="text-gray-600 leading-relaxed">Sistem CCTV 24 jam, dan penjaga malam untuk ketenangan Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="rooms" class="py-24 bg-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-secondary/10 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-accent/10 rounded-full mix-blend-multiply filter blur-3xl opacity-50 translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6 reveal-element">
                <div class="max-w-2xl">
                    <span class="text-accent font-semibold tracking-wider uppercase text-sm mb-3 block">Pilihan Terbaik</span>
                    <h2 class="text-4xl lg:text-5xl font-bold text-primary font-serif">Koleksi Kamar Kami</h2>
                </div>
                <div class="flex gap-2">
                    <button class="px-6 py-2 rounded-full border border-primary bg-primary text-white text-sm transition shadow-lg">Semua</button>
                    <button class="px-6 py-2 rounded-full border border-gray-200 text-gray-500 hover:border-primary hover:text-primary text-sm transition bg-white hover:shadow-md">AC</button>
                    <button class="px-6 py-2 rounded-full border border-gray-200 text-gray-500 hover:border-primary hover:text-primary text-sm transition bg-white hover:shadow-md">Standard</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @php
                    $availableRoomsList = collect($rooms ?? [])->filter(function($r){
                        $status = is_array($r) ? ($r['status'] ?? null) : ($r->status ?? null);
                        return $status === 'available';
                    })->values();
                @endphp

                @forelse($availableRoomsList as $room)
                    @php $photos = !empty($room->photos) ? $room->photos : []; @endphp

                    <div x-data='{
                            activeSlide: 0,
                            images: @json($photos),
                            path: "{{ asset("storage") }}/",
                            next() { if(this.images.length) this.activeSlide = (this.activeSlide === this.images.length - 1) ? 0 : this.activeSlide + 1 },
                            prev() { if(this.images.length) this.activeSlide = (this.activeSlide === 0) ? this.images.length - 1 : this.activeSlide - 1 }
                        }'
                        class="reveal-element group bg-white rounded-[30px] border border-gray-100 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-3 overflow-hidden flex flex-col h-full">

                        <div class="relative h-72 w-full bg-gray-100 overflow-hidden">
                            <div class="absolute top-4 left-4 z-20">
                                <span class="px-4 py-1.5 text-xs font-bold tracking-wide uppercase rounded-full shadow-sm backdrop-blur-md border border-white/20
                                    {{ $room->status === 'available' ? 'bg-white/95 text-green-700' : 'bg-primary/90 text-white' }}">
                                    {{ $room->status === 'available' ? 'Tersedia' : 'Penuh' }}
                                </span>
                            </div>

                            <template x-if="images.length > 0">
                                <div class="w-full h-full relative">
                                    <img :src="path + images[activeSlide]" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div x-show="images.length > 1" class="absolute inset-0 flex items-center justify-between px-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                        <button @click.prevent="prev()" class="bg-white/90 hover:bg-white text-primary w-8 h-8 flex items-center justify-center rounded-full shadow-lg transition transform hover:scale-110"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                                        <button @click.prevent="next()" class="bg-white/90 hover:bg-white text-primary w-8 h-8 flex items-center justify-center rounded-full shadow-lg transition transform hover:scale-110"><i class="fa-solid fa-chevron-right text-xs"></i></button>
                                    </div>
                                    <div x-show="images.length > 1" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-1.5 z-20">
                                        <template x-for="(img, index) in images" :key="index">
                                            <button class="h-1.5 rounded-full transition-all duration-300 shadow-sm" @click.prevent="activeSlide = index" :class="activeSlide === index ? 'bg-white w-6' : 'bg-white/60 w-1.5 hover:bg-white'"></button>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <template x-if="images.length === 0">
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                                    <i class="fa-regular fa-image text-4xl opacity-40 mb-2"></i>
                                    <span class="text-xs font-medium uppercase tracking-wide">No Photos</span>
                                </div>
                            </template>
                        </div>

                        <div class="p-7 flex flex-col flex-1 relative bg-white">
                            <div class="mb-5">
                                <h3 class="text-2xl font-serif font-bold text-primary group-hover:text-accent transition-colors">Kamar {{ $room->room_number }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Tipe: {{ $room->type ?? 'Standard' }}</p>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-6">
                                @if(!empty($room->facilities))
                                    @foreach($room->facilities as $facility)
                                        <span class="px-3 py-1 bg-bg-light border border-gray-100 rounded-lg text-xs font-medium text-gray-600 transition-colors hover:bg-secondary hover:text-primary cursor-default">{{ $facility }}</span>
                                    @endforeach
                                @else
                                    <span class="text-xs text-gray-400 italic">Detail fasilitas menyusul</span>
                                @endif
                            </div>

                            <div class="mt-auto pt-5 border-t border-gray-100 flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-400 mb-1 uppercase tracking-wider">Harga Sewa</p>
                                    <p class="text-xl font-bold text-primary font-serif">Rp {{ number_format($room->price, 0, ',', '.') }}<span class="text-xs font-sans font-normal text-gray-500">/bln</span></p>
                                </div>
                                <a href="{{ route('user.orders.create', $room) }}"
                                    class="bg-primary text-white px-5 py-2.5 rounded-full flex items-center gap-2 text-sm font-medium shadow-md transition-all duration-300 hover:bg-accent hover:shadow-lg transform hover:-translate-y-1">
                                        <span>Pesan Sekarang</span>
                                    <i class="fa-regular fa-calendar-check"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center border-2 border-dashed border-gray-200 rounded-[30px] bg-gray-50/50 reveal-element">
                        <div class="inline-block p-4 rounded-full bg-white shadow-sm mb-4 text-gray-400">
                           <i class="fa-solid fa-person-shelter text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-primary mb-2">Belum ada kamar tersedia</h3>
                        <p class="text-gray-500">Silakan periksa kembali nanti untuk update terbaru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <footer id="contact" class="bg-primary text-white pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2 space-y-6">
                    <a href="/" class="flex items-center gap-3">
                        <img src="{{ asset('img/Logo.png') }}" class="h-10 brightness-0 invert" alt="Kost Lolita">
                        <span class="font-serif text-3xl font-bold">Kost <span class="text-secondary">Lolita</span></span>
                    </a>
                    <p class="text-gray-400 leading-relaxed pr-8 max-w-md">
                        Menyediakan hunian kost premium yang nyaman, aman, dan strategis. Didesain untuk mendukung gaya hidup modern dan produktivitas Anda.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-secondary hover:text-primary transition transform hover:scale-110"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-secondary hover:text-primary transition transform hover:scale-110"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-secondary hover:text-primary transition transform hover:scale-110"><i class="fa-brands fa-twitter"></i></a>
                    </div>
                </div>

                <div class="space-y-6">
                    <h4 class="font-serif text-xl font-bold text-secondary">Menu Utama</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#hero" class="hover:text-white transition flex items-center gap-2 group"><span class="text-secondary group-hover:translate-x-1 transition-transform">•</span> Home</a></li>
                        <li><a href="#about" class="hover:text-white transition flex items-center gap-2 group"><span class="text-secondary group-hover:translate-x-1 transition-transform">•</span> Tentang Kami</a></li>
                        <li><a href="#features" class="hover:text-white transition flex items-center gap-2 group"><span class="text-secondary group-hover:translate-x-1 transition-transform">•</span> Fitur</a></li>
                        <li><a href="#rooms" class="hover:text-white transition flex items-center gap-2 group"><span class="text-secondary group-hover:translate-x-1 transition-transform">•</span> Daftar Kamar</a></li>
                    </ul>
                </div>

                <div class="space-y-6">
                    <h4 class="font-serif text-xl font-bold text-secondary">Hubungi Kami</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-map-location-dot mt-1 text-secondary"></i>
                            <span>Tenda, Kec. Langke Rembong,<br>Kota Ruteng, Nusa Tenggara Timur</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-secondary"></i>
                            <span>KostLolitaRuteng@gmail.com</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-phone text-secondary"></i>
                            <span class="font-bold text-white">081 339 240 016</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                <p>&copy; 2025 Kost Lolita. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. SCROLL SPY (Active Menu Highlight)
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-link');

            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    // Offset 150px untuk deteksi lebih akurat saat scroll
                    if (pageYOffset >= (sectionTop - 150)) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href').includes(current)) {
                        link.classList.add('active');
                    }
                });
            });

            // 2. INTERSECTION OBSERVER (Reveal Animation)
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1 // Elemen muncul saat 10% terlihat
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        // Optional: Stop observing once revealed
                        // observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            const revealElements = document.querySelectorAll('.reveal-element');
            revealElements.forEach(el => observer.observe(el));
        });
    </script>

</body>
</html>
