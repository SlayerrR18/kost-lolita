{{-- filepath: d:\Kampus\Web\kost-lolita\resources\views\sidebar\sidebarAdmin.blade.php --}}
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
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i data-feather="bookmark"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kost.index') }}"
                   class="nav-link {{ request()->routeIs('admin.kost') ? 'active' : '' }}">
                    <i data-feather="home"></i>
                    <span>Manajemen Kamar</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.account.index') }}"
                   class="nav-link {{ request()->routeIs('admin.account') ? 'active' : '' }}">
                    <i data-feather="users"></i>
                    <span> Penghuni Kost</span>
                </a>
            </li>
            <!-- Replace existing Daftar Pesanan menu item with this -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#financialDropdown">
                    <i data-feather="dollar-sign"></i>
                    <span>Riwayat Keuangan</span>
                    <i data-feather="chevron-down" class="dropdown-icon"></i>
                </a>
                <div class="collapse" id="financialDropdown">
                    <ul class="nav-submenu">
                        <li>
                            <a href="{{ route('admin.financial.income') }}"
                               class="nav-link {{ request()->routeIs('admin.financial.income') ? 'active' : '' }}">
                                <i data-feather="arrow-up-circle"></i>
                                <span>Pemasukan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.financial.expense') }}"
                               class="nav-link {{ request()->routeIs('admin.financial.expense') ? 'active' : '' }}">
                                <i data-feather="arrow-down-circle"></i>
                                <span>Pengeluaran</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="{{ route('admin.financial.pending-orders') }}"
                   class="nav-link {{ request()->routeIs('admin.financial.pending-orders') ? 'active' : '' }}">
                    <i data-feather="shopping-cart"></i>
                    <span>Konfirmasi Pesanan</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="menu-section">
        <span class="menu-title">GENERAL</span>
        <ul class="nav-menu">
            <li>
                <a href="{{ route('messages.index') }}"
                   class="nav-link {{ request()->routeIs('admin.report') ? 'active' : '' }}">
                    <i data-feather="mail"></i>
                    <span>Chat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports.index') }}"
                   class="nav-link {{ request()->routeIs('admin.report.index') ? 'active' : '' }}">
                    <i data-feather="book"></i>
                    <span>Report</span>
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

<!-- Logout Modal -->
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
                    <form action="{{ route('admin.logout') }}" method="POST">
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
/* Modern Floating Sidebar */
.sidebar {
    width: 280px;
    height: calc(100vh - 40px);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    margin: 20px;
    border-radius: 24px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    position: fixed;
    left: 0;
    top: 0;
    transition: all 0.3s ease;
}

.sidebar:hover {
    transform: translateX(5px);
}

.sidebar-header {
    padding: 16px 0;
    margin-bottom: 24px;
    border-bottom: 1px solid rgba(241, 245, 249, 0.5);
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
    border-radius: 12px;
    padding: 8px;
    background: linear-gradient(135deg, #1a7f5a 0%, #16c79a 100%);
    box-shadow: 0 4px 12px rgba(26, 127, 90, 0.15);
}

.brand-text {
    font-size: 22px;
    font-weight: 700;
    background: linear-gradient(135deg, #1a7f5a 0%, #16c79a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.brand-accent {
    color: #64748b;
    -webkit-text-fill-color: #64748b;
}

.menu-section {
    margin-bottom: 32px;
}

.menu-title {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 16px;
    display: block;
    padding-left: 12px;
    letter-spacing: 0.5px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    color: #64748b;
    text-decoration: none;
    border-radius: 12px;
    margin-bottom: 8px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, #1a7f5a 0%, #16c79a 100%);
    border-radius: 0 4px 4px 0;
    opacity: 0;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background: rgba(241, 245, 249, 0.7);
    color: #1a7f5a;
    transform: translateX(5px);
}

.nav-link.active {
    background: rgba(26, 127, 90, 0.1);
    color: #1a7f5a;
}

.nav-link.active::before {
    opacity: 1;
}

.nav-link i {
    width: 20px;
    height: 20px;
    margin-right: 12px;
    transition: all 0.3s ease;
}

.nav-link:hover i {
    transform: scale(1.1);
}

.nav-link span {
    font-size: 14px;
    font-weight: 500;
}

/* Add these styles to your existing sidebar styles */
.dropdown-toggle {
    position: relative;
    padding-right: 2.5rem;
}

.dropdown-icon {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    transition: transform 0.3s ease;
}

.dropdown-toggle[aria-expanded="true"] .dropdown-icon {
    transform: translateY(-50%) rotate(180deg);
}

.nav-submenu {
    list-style: none;
    padding: 0;
    margin: 0 0 0 2.5rem;
    border-left: 1px dashed rgba(26, 127, 90, 0.2);
}

.nav-submenu .nav-link {
    padding: 10px 16px;
    font-size: 0.9rem;
}

.nav-submenu .nav-link i {
    width: 16px;
    height: 16px;
}

/* Badge Styles */
.badge-unread {
    background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    color: white;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: auto;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
}

/* Main Content Adjustment */
.main-content {
    margin-left: 320px !important;
    padding: 20px !important;
}

/* Logout Button */
.nav-link.logout-btn {
    background: rgba(220, 38, 38, 0.1);
    color: #dc2626;
    margin-top: auto;
}

.nav-link.logout-btn:hover {
    background: rgba(220, 38, 38, 0.15);
    color: #dc2626;
}

/* Modal Improvements */
.modal-content {
    border: none;
    border-radius: 24px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.modal-body {
    padding: 2.5rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .sidebar {
        width: 80px;
        padding: 24px 12px;
    }

    .brand-text,
    .menu-title,
    .nav-link span {
        display: none;
    }

    .nav-link {
        justify-content: center;
        padding: 12px;
    }

    .nav-link i {
        margin-right: 0;
    }

    .main-content {
        margin-left: 120px !important;
    }

    .badge-unread {
        position: absolute;
        top: 0;
        right: 0;
        transform: translate(50%, -50%);
        padding: 4px;
        min-width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
}
</style>
<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Initialize feather icons
    feather.replace();

    // Reinitialize feather icons when modal opens
    const logoutModal = document.getElementById('logoutModal');
    logoutModal.addEventListener('shown.bs.modal', function() {
        feather.replace();
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
