<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('img/Logo-no-bg.png') }}" alt="Kost Lolita" height="40">
            Kost Lolita
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    {{-- Logika Cerdas: Jika di homepage, gunakan anchor link. Jika tidak, arahkan ke homepage + anchor --}}
                    <a class="nav-link" href="{{ Request::is('/') ? '#home' : route('home') . '#home' }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ Request::is('/') ? '#kamar' : route('home') . '#kamar' }}">Kamar</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="{{ Request::is('/') ? '#fasilitas' : route('home') . '#fasilitas' }}">Fasilitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ Request::is('/') ? '#tentang' : route('home') . '#tentang' }}">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ Request::is('/') ? '#kontak' : route('home') . '#kontak' }}">Kontak</a>
                </li>
            </ul>
            <div class="d-flex">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-light">Register</a>
                    @endif
                @else
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Selamat Datang, {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            @if(Auth::user()->role == 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">Dashboard User</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

