<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Cinema')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stats-card {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
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
                    <div class="bg-white shadow-sm p-3 mb-4">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
