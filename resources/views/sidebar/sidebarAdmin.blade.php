{{-- filepath: d:\Kampus\Web\kost-lolita\resources\views\sidebar\sidebarAdmin.blade.php --}}
@php
    // Logika penentuan status aktif yang lebih spesifik
    $dashboardActive = request()->routeIs('admin.dashboard');
    $manajemenKamarActive = request()->routeIs('admin.kost*');
    $penghuniActive = request()->routeIs('admin.account*');
    $konfirmasiActive = request()->routeIs('admin.financial.pending-orders');
    $riwayatTransaksiOpen = request()->routeIs('admin.financial.income') || request()->routeIs('admin.financial.expense');
    $laporanActive = request()->routeIs('admin.reports*');
    $messagesActive = request()->routeIs('messages.*');
@endphp

<div class="sidebar">
    <div class="sidebar-header">
        <span class="brand">
            <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="brand-logo">
            <span class="brand-text">
                Kost <span class="brand-accent">Lolita</span>
            </span>
        </span>
    </div>

    <div class="menu-section">
        <span class="menu-title">MENU</span>
        <ul class="nav-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ $dashboardActive ? 'active' : '' }}">
                    <i data-feather="layout"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kost.index') }}"
                   class="nav-link {{ $manajemenKamarActive ? 'active' : '' }}">
                    <i data-feather="home"></i>
                    <span>Manajemen Kamar</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.account.index') }}"
                   class="nav-link {{ $penghuniActive ? 'active' : '' }}">
                    <i data-feather="users"></i>
                    <span>Penghuni Kost</span>
                </a>
            </li>

            {{-- Bug #1: Konfirmasi Pesanan tidak lagi mengaktifkan dropdown lain --}}
            <li>
                <a href="{{ route('admin.financial.pending-orders') }}"
                   class="nav-link {{ $konfirmasiActive ? 'active' : '' }}">
                    <i data-feather="shopping-bag"></i>
                    <span>Konfirmasi Pesanan</span>
                </a>
            </li>

            {{-- Bug #2: Dropdown hanya aktif pada rute Pemasukan/Pengeluaran --}}
            <li class="nav-item has-submenu">
                <a
                  href="#financialDropdown"
                  class="nav-link dropdown-toggle {{ $riwayatTransaksiOpen ? 'active' : '' }}"
                  data-bs-toggle="collapse"
                  role="button"
                  aria-expanded="{{ $riwayatTransaksiOpen ? 'true' : 'false' }}"
                  aria-controls="financialDropdown">
                  <i data-feather="credit-card"></i>
                  <span>Riwayat Transaksi</span>
                  <i data-feather="chevron-down" class="dropdown-icon"></i>
                </a>

                <div class="collapse {{ $riwayatTransaksiOpen ? 'show' : '' }}" id="financialDropdown">
                    <ul class="nav-submenu">
                        <li>
                            <a href="{{ route('admin.financial.income') }}"
                               class="nav-link {{ request()->routeIs('admin.financial.income') ? 'active' : '' }}">
                                <i data-feather="trending-up"></i>
                                <span>Pemasukan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.financial.expense') }}"
                               class="nav-link {{ request()->routeIs('admin.financial.expense') ? 'active' : '' }}">
                                <i data-feather="trending-down"></i>
                                <span>Pengeluaran</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

    <div class="menu-section">
        <span class="menu-title">AKUN</span>
        <ul class="nav-menu">
              <li>
                <a href="{{ route('admin.reports.index') }}"
                   class="nav-link {{ $laporanActive ? 'active' : '' }}">
                    <i data-feather="file-text"></i>
                    <span>Riwayat Laporan & Keluhan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('messages.index') }}"
                   class="nav-link {{ $messagesActive ? 'active' : '' }}">
                    <i data-feather="message-square"></i>
                    <span>Pesan</span>
                </a>
            </li>
            <li>
                <button type="button" class="nav-link w-100" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i data-feather="log-out"></i>
                    <span>Logout</span>
                </button>
            </li>
        </ul>
    </div>
</div>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="text-warning mb-4">
                    <i data-feather="alert-circle" style="width: 64px; height: 64px;"></i>
                </div>
                <h5 class="modal-title mb-3" id="logoutModalLabel">Konfirmasi Logout</h5>
                <p>Apakah Anda yakin ingin keluar dari sistem?</p>

                <div class="mt-4 d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i data-feather="x"></i>
                        Batal
                    </button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i data-feather="log-out"></i>
                            Ya, Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Sidebar Utama */
.sidebar {
    width: 280px;
    height: 100vh;
    background: var(--surface);
    position: fixed;
    left: 0;
    top: 0;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

/* Header & Brand */
.sidebar-header {
    border-bottom: 1px solid var(--ring);
    padding-bottom: 1.5rem;
    margin-bottom: 1.5rem;
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.brand-logo {
    width: 42px;
    height: 42px;
    object-fit: contain;
    border-radius: var(--radius-md);
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
    padding: 8px;
}

.brand-text {
    font-size: 22px;
    font-weight: 700;
    color: var(--primary);
}

.brand-accent {
    color: var(--muted);
}

/* Menu Section */
.menu-section {
    margin-bottom: 1.5rem;
}

.menu-title {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    margin-bottom: 1rem;
    display: block;
    padding-left: 12px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    color: var(--muted);
    text-decoration: none;
    border-radius: var(--radius-md);
    margin-bottom: 8px;
    transition: all 0.25s ease;
    position: relative;
}

.nav-link:hover {
    background: var(--secondary);
    color: var(--primary);
}

.nav-link.active {
    background: rgba(26, 127, 90, 0.1);
    color: var(--primary);
}

.nav-link i {
    width: 20px;
    height: 20px;
    margin-right: 12px;
}

.nav-link span {
    font-size: 14px;
    font-weight: 500;
}

/* Dropdown */
.nav-item.has-submenu .nav-link {
    justify-content: space-between;
}

.dropdown-icon {
    width: 16px;
    height: 16px;
    transition: transform 0.3s ease;
    margin-right: 0 !important;
}

.nav-link[aria-expanded="true"] .dropdown-icon {
    transform: rotate(180deg);
}

.nav-submenu {
    list-style: none;
    padding: 0;
    margin: 0.5rem 0 0.5rem 2.25rem;
    border-left: 1px solid var(--ring);
}

.nav-submenu a.nav-link {
    padding-left: 1rem;
    margin-bottom: 4px;
}

/* Modal Logout */
.modal-content {
    border: none;
    border-radius: var(--radius-lg);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.modal-body {
    padding: 2.5rem;
    text-align: center;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
}

.modal-body .text-warning i {
    width: 4rem;
    height: 4rem;
    color: var(--warning);
}

.modal-footer {
    padding: 1rem 2rem;
}

/* Buttons */
.btn {
    border-radius: var(--radius-md);
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-secondary {
    background: var(--secondary);
    color: var(--ink);
}

.btn-danger {
    background: var(--danger);
    color: white;
}

/* Responsive */
@media (max-width: 1024px) {
    .sidebar {
        width: 80px;
        padding: 24px 12px;
        align-items: center;
    }

    .brand-text,
    .menu-title,
    .nav-link span,
    .dropdown-icon {
        display: none;
    }

    .nav-link {
        justify-content: center;
        padding: 12px;
    }

    .nav-link i {
        margin-right: 0;
    }

    .sidebar-header {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 1rem;
    }

    .nav-submenu {
        display: none !important;
    }

    .dropdown-toggle[aria-expanded="true"] + .nav-submenu {
        display: block !important;
    }
}
</style>

<script src="https://unpkg.com/feather-icons"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi ikon
    feather.replace();

    // Pastikan ikon tetap benar ketika collapse dibuka/tutup via JS
    const financial = document.getElementById('financialDropdown');
    if (financial) {
        financial.addEventListener('shown.bs.collapse', () => feather.replace());
        financial.addEventListener('hidden.bs.collapse', () => feather.replace());
    }

    // Modal logout juga re-render ikon saat dibuka
    const logoutModal = document.getElementById('logoutModal');
    if (logoutModal) {
        logoutModal.addEventListener('shown.bs.modal', () => feather.replace());
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
