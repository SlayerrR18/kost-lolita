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
                <a href="{{ route('user.dashboard') }}"
                   class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i data-feather="bookmark"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.contract') }}"
                   class="nav-link {{ request()->routeIs('user.contract.*') ? 'active' : '' }}">
                    <i data-feather="home"></i>
                    <span>Kontrak</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.history.index') }}"
                   class="nav-link {{ request()->routeIs('user.history.index') ? 'active' : '' }}">
                    <i data-feather="credit-card"></i>
                    <span>Riwayat Transaksi</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="menu-section">
        <span class="menu-title">GENERAL</span>
        <ul class="nav-menu">
            <li>
                <a href="{{ route('messages.index') }}"
                   class="nav-link {{ request()->is('admin/report*') ? 'active' : '' }}">
                    <i data-feather="file-text"></i>
                    <span>Chat</span>
                    @if(isset($admin) && $admin->unread_count > 0)
                        <span class="badge-unread">{{ $admin->unread_count }}</span>
                    @endif
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
                    <!-- Replace the existing logout form -->
                    <form action="{{ route('user.logout') }}" method="POST" class="d-inline">
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
.sidebar {
    width: 280px;
    min-height: 100vh;
    background: #fff;
    padding: 24px;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 20px rgba(0,0,0,0.1);
}

.sidebar-header {
    padding: 16px 0;
    margin-bottom: 24px;
    border-bottom: 1px solid #f1f5f9;
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.brand-logo {
    width: 32px;
    height: 32px;
    object-fit: contain;
    border-radius: 50%;
}

.brand-text {
    font-size: 20px;
    font-weight: 600;
    color: #1a7f5a;
}

.brand-accent {
    color: #64748b;
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
}

.nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px;
    color: #64748b;
    text-decoration: none;
    border-radius: 12px;
    margin-bottom: 4px;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background: #f1f5f9;
    color: #1a7f5a;
}

.nav-link.active {
    background: #e6f4ea;
    color: #1a7f5a;
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

.nav-link button {
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

form .nav-link:hover {
    background: #f1f5f9;
    color: #1a7f5a;
}

.logout-form {
    margin: 0;
    padding: 0;
}

.logout-form .nav-link {
    background: transparent;
    border: none;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    padding: 12px;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.logout-form .nav-link:hover {
    background: #f1f5f9;
    color: #1a7f5a;
}

.logout-form .nav-link i {
    width: 20px;
    height: 20px;
    margin-right: 12px;

}

.modal-content {
    border: none;
    border-radius: 16px;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
}

.modal-body {
    padding: 2rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn i {
    width: 18px;
    height: 18px;
}

.btn-secondary {
    background: #f1f5f9;
    border: none;
    color: #64748b;
}

.btn-secondary:hover {
    background: #e2e8f0;
    color: #475569;
}

.btn-danger {
    background: #dc2626;
    border: none;
    color: white;
}

.btn-danger:hover {
    background: #b91c1c;
    transform: translateY(-1px);
}

.text-warning i {
    color: #eab308;
    stroke-width: 1.5;
}

.badge-unread {
    background: #dc2626;
    color: #fff;
    border-radius: 50%;
    padding: 2px 8px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-left: 8px;
    min-width: 24px;
    text-align: center;
    display: inline-block;
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
