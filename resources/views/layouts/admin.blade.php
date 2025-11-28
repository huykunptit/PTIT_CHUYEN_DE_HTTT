<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Cinema')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.4/dist/bootstrap-table.min.css">
    <style>
        :root {
            --primary-color: #0052cc;
            --secondary-color: #0a84ff;
            --accent-color: #ff4d4f;
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
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
        }
        .top-bar {
            background: var(--surface);
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }
        .card {
            border: none;
            border-radius: 18px;
            background: var(--surface);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.5s ease-out;
        }

        .card:hover {
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
            transform: translateY(-4px);
        }
        .stats-card {
            background: linear-gradient(135deg, #0052cc, #0a84ff);
            color: white;
            border: none;
        }
        .text-muted {
            color: var(--text-muted) !important;
        }
        .badge.bg-primary {
            background: var(--accent-color) !important;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-3">
                        <h4 class="text-white mb-4">
                            <i class="fas fa-film me-2"></i>Cinema Admin
                        </h4>
                    </div>
                    
                    <nav class="nav flex-column px-3">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.movies.*') ? 'active' : '' }}" 
                           href="{{ route('admin.movies.index') }}">
                            <i class="fas fa-film me-2"></i>Quản lý phim
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.cinemas.*') ? 'active' : '' }}" 
                           href="{{ route('admin.cinemas.index') }}">
                            <i class="fas fa-building me-2"></i>Quản lý rạp
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.showtimes.*') ? 'active' : '' }}" 
                           href="{{ route('admin.showtimes.index') }}">
                            <i class="fas fa-clock me-2"></i>Lịch chiếu
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" 
                           href="{{ route('admin.bookings.index') }}">
                            <i class="fas fa-ticket-alt me-2"></i>Đặt vé
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}" 
                           href="{{ route('admin.rooms.index') }}">
                            <i class="fas fa-door-open me-2"></i>Phòng chiếu
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" 
                           href="{{ route('admin.reports.index') }}">
                            <i class="fas fa-chart-bar me-2"></i>Báo cáo
                        </a>
                        
                        <hr class="text-white">
                        
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-2"></i>Về trang chủ
                        </a>
                        
                        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                        </a>
                        
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <!-- Top Bar -->
                    <div class="top-bar p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                            <div class="d-flex align-items-center">
                                <span class="text-muted me-3">
                                    <i class="fas fa-user me-1"></i>{{ Auth::user()->name ?? 'Admin' }}
                                </span>
                                <span class="badge bg-primary">
                                    <i class="fas fa-crown me-1"></i>Admin
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-4">
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

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/locale/bootstrap-table-vi-VN.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/export/bootstrap-table-export.min.js"></script>
    <script src="https://unpkg.com/tableexport.jquery.plugin@1.28.0/tableExport.min.js"></script>
    @auth
    @if(config('broadcasting.default') === 'pusher' && config('broadcasting.connections.pusher.key'))
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script>
            (function () {
                try {
                    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                        cluster: '{{ config('broadcasting.connections.pusher.options.cluster', 'mt1') }}',
                        encrypted: true,
                        authEndpoint: '/broadcasting/auth',
                        auth: {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }
                    });

                    const channel = pusher.subscribe('private-admin.notifications');
                    ['booking.created', 'payment.success', 'booking.cancelled'].forEach(eventName => {
                        channel.bind(eventName, function (data) {
                            renderToast(buildMessage(data), data.level || 'info');
                        });
                    });
                } catch (error) {
                    console.error('Pusher admin init error:', error);
                }

                function buildMessage(data) {
                    if (data.message) {
                        return data.message;
                    }
                    const name = data.user_name || 'Khách hàng';
                    const action = data.action_label || 'Đặt vé';
                    const status = data.status_label || data.status || '';
                    return status ? `${name} - ${action} - ${status}` : `${name} - ${action}`;
                }

                function renderToast(message, level) {
                    const variants = {
                        success: 'success',
                        warning: 'warning',
                        danger: 'danger',
                        info: 'info'
                    };
                    const alertType = variants[level] || 'info';
                    const toast = document.createElement('div');
                    toast.className = `alert alert-${alertType} alert-dismissible fade show position-fixed shadow`;
                    toast.style.cssText = 'top: 90px; right: 20px; z-index: 1060; min-width: 320px;';
                    toast.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">${message}</div>
                            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 6000);
                }
            })();
        </script>
    @endif
    @endauth
    @yield('scripts')
</body>
</html>
