<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title') - Kost Lolita</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
    <style>
        :root{
            --sidebar-w: 280px;
            --sidebar-gap: 20px;
            --main-left-margin-lg: calc(var(--sidebar-w) + var(--sidebar-gap) * 2);
            --main-left-margin-sm: calc(80px + var(--sidebar-gap) * 2);

            --primary: #1a7f5a;
            --primary-2: #16c79a;
            --secondary: #f1f5f9;
            --surface: #ffffff;
            --bg: #f8fafc;
            --ink: #1e293b;
            --muted: #64748b;
            --ring: #e2e8f0;
            --success: #16a34a;
            --danger: #dc2626;
            --info: #0ea5e9;
            --warning: #f59e0b;
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.1);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 24px;
        }

        body {
            min-height: 100vh;
            background: var(--bg);
            font-family: 'Poppins', sans-serif;
            color: var(--ink);
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* === Sidebar: Floating === */
        .sidebar {
            width: var(--sidebar-w);
            height: calc(100vh - var(--sidebar-gap) * 2);
            margin: var(--sidebar-gap);
            position: fixed; left: 0; top: 0;
            background: rgba(255, 255, 255, .95);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-lg);
            padding: 24px;
            display: flex; flex-direction: column;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .08);
            overflow-y: auto;
            box-sizing: border-box;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .main-content {
            flex: 1;
            margin-left: var(--main-left-margin-lg);
            padding: 32px;
            background: var(--bg);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .main-content > * {
            width: min(100%, 1600px);
            margin-inline: auto;
            padding: 0 1rem;
        }

        .sidebar-header {
            border-bottom: 1px solid var(--ring);
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .brand {
            display: flex; align-items: center; gap: 12px;
        }
        .brand-logo {
            width: 42px; height: 42px; object-fit: contain;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
            padding: 8px;
        }
        .brand-text {
            font-size: 22px; font-weight: 700; color: var(--primary);
        }
        .brand-accent {
            color: var(--muted);
        }

        .menu-section {
            margin-bottom: 1.5rem;
        }
        .menu-title {
            font-size: 12px; font-weight: 600; color: var(--muted);
            margin-bottom: 1rem; display: block; padding-left: 12px;
            letter-spacing: 0.5px; text-transform: uppercase;
        }
        .nav-menu {
            list-style: none; padding: 0; margin: 0;
        }
        .nav-link {
            display: flex; align-items: center; padding: 12px 16px;
            color: var(--muted); text-decoration: none !important;
            border-radius: var(--radius-md); margin-bottom: 8px;
            transition: all 0.25s ease; position: relative;
        }
        .nav-link:hover{ background:var(--secondary); color:var(--primary) }
        .nav-link.active{ background:rgba(26,127,90,.1); color:var(--primary) }
        .nav-link i{ width:20px;height:20px; margin-right:12px }
        .nav-link span{ font-size:14px; font-weight:500; }
        .nav-link[aria-expanded="true"] .dropdown-icon { transform: rotate(180deg); }
        .dropdown-icon { transition: transform 0.3s ease; }
        .nav-submenu {
            list-style: none; padding: 0;
            margin: 0.5rem 0 0.5rem 2.25rem;
            border-left: 1px solid var(--ring);
        }
        .nav-submenu a.nav-link { padding-left: 1rem; margin-bottom: 4px; }
        .nav-item.has-submenu .nav-link { justify-content: space-between; }

        .modal-content { border: none; border-radius: var(--radius-lg); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); }
        .modal-body { padding: 2.5rem; text-align: center; }
        .btn-secondary { background: var(--secondary); color: var(--ink); }
        .btn-danger { background: var(--danger); color: white; }

        @media (max-width: 1024px) {
            .sidebar {
                width: 80px;
                padding: 24px 12px;
                align-items: center;
                margin: var(--sidebar-gap);
                height: calc(100vh - var(--sidebar-gap) * 2);
            }
            .main-content {
                margin-left: var(--main-left-margin-sm);
                padding: 24px;
            }
            .sidebar .brand-text,
            .sidebar .menu-title,
            .sidebar .nav-link span,
            .sidebar .dropdown-icon {
                display: none;
            }
            .sidebar .nav-link {
                justify-content: center;
                padding: 12px;
            }
            .sidebar .nav-link i {
                margin-right: 0;
            }
            .sidebar .nav-submenu {
                display: none !important;
            }
            .sidebar .nav-link[aria-expanded="true"] + .nav-submenu {
                display: block !important;
                position: absolute;
                top: 0;
                left: 100%;
                width: 200px;
                background: var(--surface);
                padding: 1rem;
                border-radius: var(--radius-md);
                box-shadow: var(--shadow-md);
            }
        }
    </style>
    @stack('css')
</head>
<body>
    <div class="wrapper">
        @include('sidebar.sidebarUser')

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            document.body.addEventListener('shown.bs.modal', function() {
                feather.replace();
            });
            document.body.addEventListener('shown.bs.collapse', function() {
                feather.replace();
            });
        });
    </script>
    @stack('js')
</body>
</html>
