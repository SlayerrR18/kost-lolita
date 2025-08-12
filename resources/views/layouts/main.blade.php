<!-- resources/views/layouts/main.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
    <style>
:root{
  --sidebar-w: 280px;
  --sidebar-gap: 40px;   /* karena sidebar pakai margin:20px */
  --container-max: 1160px;
  --gutter: 24px;
}

body{min-height:100vh;background:#f8fafc}
.wrapper{display:flex;min-height:100vh}

/* === Sidebar: satu-satunya definisi === */
.sidebar{
  width: var(--sidebar-w);
  height: calc(100vh - 40px);
  margin: 20px;                       /* jarak dari tepi */
  position: fixed; left:0; top:0;
  background: rgba(255,255,255,.95);
  backdrop-filter: blur(10px);
  border-radius: 24px;
  padding: 24px;
  display:flex; flex-direction:column;
  box-shadow: 0 8px 32px rgba(0,0,0,.08);
  overflow-y: auto;                    /* PENTING: cegah tumpah */
  box-sizing: border-box;
  z-index: 1000;
}

/* Letak konten utama */
.main-content{
  flex:1;
  margin-left: calc(var(--sidebar-w) + var(--sidebar-gap));
  padding: 32px 0;
  background:#f8fafc;
  min-height:100vh;
  transition: margin-left .3s ease;
}

/* Lebarkan section besar agar rapi di tengah */
.main-content > .page-header,
.main-content > .stats-grid,
.main-content > .card,
.main-content > .content-card,
.main-content > .table-responsive{
  width: min(var(--container-max), calc(100% - (var(--gutter) * 2)));
  margin-inline: auto;
}

/* Link di sidebar */
.sidebar .nav-link{
  display:flex; align-items:center; gap:12px;
  padding:12px 16px;
  border-radius:12px;
  color:#64748b; text-decoration:none !important;
  transition:.3s;
  position:relative; overflow:hidden;
}
.sidebar .nav-link:hover{ background:rgba(241,245,249,.7); color:#1a7f5a }
.sidebar .nav-link.active{ background:rgba(26,127,90,.1); color:#1a7f5a }
.sidebar .nav-link i{ width:20px;height:20px }

/* Responsive: sidebar mini */
@media (max-width:1024px){
  :root{ --sidebar-w: 80px; }
  .main-content{ margin-left: calc(var(--sidebar-w) + var(--sidebar-gap)); padding:24px 0 }
}
</style>
    @stack('css')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('sidebar.sidebarAdmin')

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('js')
</body>
</html>
