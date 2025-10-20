<nav class="navbar navbar-expand-lg fixed-top custom-navbar">
    <div class="container">
        <a class="navbar-brand me-5" href="{{ route('home') }}">
            <img src="{{ asset('img/Logo-no-bg.png') }}" alt="Kost Lolita Logo" class="navbar-logo me-2">
            <span class="glacia-logo">Kost Lolita</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-dot-circle nav-dot me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('kamar*') ? 'active' : '' }}" href="#kamar">
                        <i class="fas fa-dot-circle nav-dot me-1"></i> Kamar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#lokasi">
                        <i class="fas fa-dot-circle nav-dot me-1"></i> Lokasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tentang">
                        <i class="fas fa-dot-circle nav-dot me-1"></i> Tentang
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center">
                @guest
                    <li class="nav-item me-2">
                        <a class="nav-link" href="{{ route('login') }}">
                             Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-outline-light rounded-pill px-3" href="{{ route('register') }}">
                             Daftar
                        </a>
                    </li>
                    @else
                    <li class="nav-item dropdown user-dropdown-nav">
                        <a class="nav-link dropdown-toggle d-flex align-items-center user-toggle-button"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                            <div class="d-inline-flex align-items-center">
                                <i class="far fa-user-circle fs-5 me-2"></i>
                                Hi, {{ strtok(Auth::user()->name, " ") }}
                                <i class="fas fa-chevron-down ms-1 dropdown-icon"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu">
                            @if(Auth::user()->hasApprovedOrder())
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2 text-primary"></i> Dashboard
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item text-danger" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Keluar
                                </a>
                            </li>
                        </ul>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </ul>
        </div>
    </div>
</nav>
