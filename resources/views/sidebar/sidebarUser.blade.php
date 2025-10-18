<aside class="sidebar">
    {{-- Header --}}
    <div class="sidebar-header">
        <a href="{{ route('home') }}" class="brand">
            <img src="{{ asset('img/Logo-no-bg.png') }}" class="brand-logo" alt="Logo">
            <div>
                <div class="brand-text">Kost <span class="brand-accent">Lolita</span></div>
            </div>
        </a>
    </div>

    {{-- Menu Utama --}}
    <div class="menu-section">
        <span class="menu-title">Menu Pengguna</span>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i data-feather="layout"></i>
                    <span>Dashboard Saya</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.history.index') }}" class="nav-link {{ request()->routeIs('user.history.index') ? 'active' : '' }}">
                    <i data-feather="archive"></i>
                    <span>Riwayat Sewa</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.contract') }}" class="nav-link {{ request()->routeIs('user.contract') ? 'active' : '' }}">
                    <i data-feather="file-text"></i>
                    <span>Kontrak & Perpanjang</span>
                </a>
            </li>
        </ul>
    </div>

     {{-- Menu Lainnya --}}
     <div class="menu-section">
        <span class="menu-title">Lainnya</span>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('user.reports.index') }}" class="nav-link {{ request()->routeIs('user.reports.*') ? 'active' : '' }}">
                    <i data-feather="alert-triangle"></i>
                    <span>Laporan Masalah</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('messages.index') }}" class="nav-link {{ request()->routeIs('messages.index') ? 'active' : '' }}">
                    <i data-feather="message-square"></i>
                    <span>Pesan</span>
                </a>
            </li>
        </ul>
    </div>


    {{-- Logout Section --}}
    <div class="logout-section">
        <button type="button" class="btn-logout nav-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i data-feather="log-out"></i>
            <span>Logout</span>
        </button>
    </div>
</aside>

<!-- Logout Modal (Sama seperti di sidebar admin) -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="mb-3">Konfirmasi Logout</h4>
                <p class="text-muted">Apakah Anda yakin ingin keluar dari sesi ini?</p>
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    {{-- Form ini akan mengarah ke route 'logout' yang benar --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Ya, Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
