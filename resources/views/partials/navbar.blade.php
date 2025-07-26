<nav class="navbar-modern">
    <div class="navbar-container">
        <div class="navbar-left">
            <a href="/" class="navbar-brand">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="navbar-logo">
                Kost Lolita
            </a>
        </div>

        <div class="navbar-center">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
            <a href="{{ route('kamar') }}" class="nav-link {{ request()->routeIs('kamar') ? 'active' : '' }}">Kamar</a>
        </div>

        <div class="navbar-right">
            <a href="{{ route('login') }}" class="nav-contact">
                <i class="fas fa-user"></i> Login   
            </a>
        </div>
    </div>
</nav>
