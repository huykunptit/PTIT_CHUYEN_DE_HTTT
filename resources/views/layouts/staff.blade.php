<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Panel - Cinema')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.4/dist/bootstrap-table.min.css">
    <style>
        :root {
            --primary-color: #0052cc;
            --secondary-color: #0a84ff;
            --accent-color: #10b981;
            --sidebar-bg: linear-gradient(160deg, #0052cc 0%, #0a84ff 100%);
            --sidebar-hover: rgba(255, 255, 255, 0.15);
            --text-primary: #0f172a;
            --text-muted: #64748b;
            --surface: #ffffff;
            --surface-muted: #f5f7fb;
        }

        * {
            scroll-behavior: smooth;
        }

        body {
            background: var(--surface-muted);
            color: var(--text-primary);
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-bg);
            box-shadow: 6px 0 18px rgba(15, 23, 42, 0.12);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 12px 20px;
            border-radius: 10px;
            margin: 4px 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: rgba(255, 255, 255, 0.8);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #ffffff;
            background: var(--sidebar-hover);
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link:hover::before,
        .sidebar .nav-link.active::before {
            transform: scaleY(1);
        }

        .main-content {
            background-color: var(--surface-muted);
            min-height: 100vh;
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
            margin-bottom: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.5s ease-out;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
            transform: translateY(-4px);
        }

        .card-header {
            background: var(--surface);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            padding: 20px;
            border-radius: 12px 12px 0 0;
            font-weight: 600;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 8px 0;
        }

        .stat-card .stat-label {
            opacity: 0.9;
            font-size: 0.9rem;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-4">
                    <h4 class="text-white mb-4">
                        <i class="fas fa-user-tie me-2"></i>Staff Panel
                    </h4>
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}" href="{{ route('staff.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('staff.bookings.*') ? 'active' : '' }}" href="{{ route('staff.bookings.index') }}">
                            <i class="fas fa-ticket-alt me-2"></i>Đặt vé
                        </a>
                        <a class="nav-link {{ request()->routeIs('staff.checkin.*') ? 'active' : '' }}" href="{{ route('staff.checkin.show') }}">
                            <i class="fas fa-qrcode me-2"></i>Check-in
                        </a>
                        <hr class="text-white-50 my-3">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-2"></i>Về trang chủ
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link text-start w-100 border-0 bg-transparent">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/locale/bootstrap-table-vi-VN.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/export/bootstrap-table-export.min.js"></script>
    <script src="https://unpkg.com/tableexport.jquery.plugin@1.10.21/tableExport.min.js"></script>
    @yield('scripts')
</body>
</html>

