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
    <!-- Custom CSS -->
    <style>
        /* Base styles */
        body {
            min-height: 100vh;
            background: #f6f8fa;
        }

        .main-content {
            margin-left: 320px;
            min-height: 100vh;
            padding: 24px 16px;
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 1024px) {
            .main-content {
                margin-left: 100px;
            }
        }
        .content { padding-left: 20px; padding-right: 20px; }
        @media (min-width:1280px){ .content{ padding-left:28px; padding-right:28px; } }
        
    </style>
    @stack('css')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @include('sidebar.sidebarUser')

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            @yield('content')
        </div>
    </div>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
    @stack('js')
</body>
</html>
