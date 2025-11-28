<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cinema - Hệ thống đặt vé xem phim')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0052cc;
            --secondary-color: #0a84ff;
            --dark-bg: #ffffff;
            --card-bg: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #4b5563;
            --accent-color: #ff4d4f;
        }

        * {
            scroll-behavior: smooth;
        }

        body {
            background-color: #f5f7fb;
            color: var(--text-primary);
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Smooth fade-in animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
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

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .slide-in-right {
            animation: slideInRight 0.6s ease-out;
        }

        /* Header Styles */
        .navbar {
            background: #ffffff !important;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-color) !important;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
            color: var(--secondary-color) !important;
        }

        .navbar-brand .tagline {
            font-size: 0.75rem;
            color: var(--text-secondary);
            display: block;
            margin-top: -5px;
        }

        .search-bar {
            background: #f0f4ff;
            border: 1px solid rgba(0, 82, 204, 0.2);
            border-radius: 25px;
            color: var(--text-primary);
            padding: 10px 20px;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .search-bar::placeholder {
            color: #94a3b8;
            transition: opacity 0.3s ease;
        }

        .search-bar:focus {
            background: #ffffff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 82, 204, 0.15), 0 4px 12px rgba(0, 82, 204, 0.1);
            color: var(--text-primary);
            outline: none;
            transform: translateY(-2px);
        }

        .search-bar:focus::placeholder {
            opacity: 0.5;
        }

        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            padding: 0.5rem 1rem !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-50%);
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .nav-link:hover::after {
            width: 80%;
        }

        /* Hero Section */
        .hero-section {
            height: 80vh;
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
        }

        .hero-overlay {
            background: linear-gradient(45deg, rgba(0,0,0,0.8), rgba(0,0,0,0.4));
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 600px;
        }

        .movie-title {
            font-size: 3.5rem;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        }

        .movie-subtitle {
            font-size: 1.2rem;
            color: #ffffff;
            margin-bottom: 1.5rem;
        }

        .movie-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .meta-badge {
            background: rgba(255, 255, 255, 0.9);
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .imdb-badge {
            background: var(--accent-color);
            color: #000000;
        }

        .movie-genres {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .genre-tag {
            background: var(--primary-color);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .play-btn {
            background: var(--primary-color);
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            color: white;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.4);
        }

        .play-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.6);
        }

        .action-btn {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: var(--text-primary);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.1);
        }

        /* Categories Section */
        .categories-section {
            padding: 4rem 0;
            background: #ffffff;
        }

        .section-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
            color: var(--text-primary);
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .category-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            color: white;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 82, 204, 0.15);
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .category-card::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }

        .category-card:hover::before {
            opacity: 1;
        }

        .category-card:hover::after {
            width: 300px;
            height: 300px;
        }

        .category-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 12px 40px rgba(0, 82, 204, 0.3);
        }

        .category-card h3 {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .category-card a {
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Movie Rows */
        .movie-rows {
            padding: 2rem 0;
        }

        .movie-row {
            margin-bottom: 3rem;
        }

        .row-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .row-title a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .movies-scroll {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding: 1rem 0;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) transparent;
        }

        .movies-scroll::-webkit-scrollbar {
            height: 6px;
        }

        .movies-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .movies-scroll::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }

        .movie-card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .movie-card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .movie-card-hover:hover .movie-poster {
            transform: scale(1.05);
        }

        .movie-card {
            min-width: 200px;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: relative;
        }

        .movie-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 82, 204, 0.05), rgba(10, 132, 255, 0.05));
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 1;
        }

        .movie-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 32px rgba(0, 82, 204, 0.2);
            border-color: var(--primary-color);
        }

        .movie-card:hover::before {
            opacity: 1;
        }

        .movie-card * {
            position: relative;
            z-index: 2;
        }

        .movie-poster {
            width: 100%;
            height: 280px;
            object-fit: cover;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 3rem;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .movie-card:hover .movie-poster {
            transform: scale(1.1);
        }

        .movie-info {
            padding: 1rem;
        }

        .movie-info h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .movie-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
        }

        .imdb-badge {
            background: var(--accent-color);
            color: var(--dark-bg);
        }

        .year-badge {
            background: #e9ecef;
            color: var(--text-primary);
        }

        /* Footer */
        .footer {
            background: #ffffff;
            padding: 3rem 0 2rem;
            border-top: 1px solid #dee2e6;
        }

        /* Button improvements */
        .btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 82, 204, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Card improvements */
        .card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
        }

        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s ease-in-out infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        /* Smooth scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* Alert improvements */
        .alert {
            animation: slideInRight 0.4s ease-out;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Dropdown improvements */
        .dropdown-menu {
            border: none;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            animation: fadeIn 0.3s ease-out;
        }

        .dropdown-item {
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(90deg, rgba(0, 82, 204, 0.1), rgba(10, 132, 255, 0.1));
            transform: translateX(5px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                height: 60vh;
            }
            
            .movie-title {
                font-size: 2.5rem;
            }
            
            .search-bar {
                width: 100% !important;
                width: 100%;
                margin: 1rem 0;
            }
            
            .categories-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1rem;
            }
            
            .movie-card {
                min-width: 150px;
            }

            .nav-link::after {
                display: none;
            }
        }
        .search-bar:focus::placeholder {
            opacity: 0.5;
        }

        /* Search Suggestions Styles */
        .search-container {
            position: relative;
        }

        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            max-height: 400px;
            overflow-y: auto;
            z-index: 1050;
            margin-top: 8px;
        }

        .search-suggestion-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            border-bottom: 1px solid #f1f3f5;
        }

        .search-suggestion-item:last-child {
            border-bottom: none;
        }

        .search-suggestion-item:hover,
        .search-suggestion-item.active {
            background: #f8f9fa;
        }

        .search-suggestion-poster {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
            flex-shrink: 0;
        }

        .search-suggestion-info {
            flex: 1;
            min-width: 0;
        }

        .search-suggestion-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .search-suggestion-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .search-suggestion-badge {
            font-size: 0.7rem;
            padding: 2px 6px;
        }
        /* Pagination Styles */
        Đây là code CSS hoàn chỉnh cho pagination: css/* Pagination Styles */
        .pagination {
            gap: 0.5rem;
        }

        .pagination .page-item {
            margin: 0;
        }

        .pagination .page-link {
            border: none;
            border-radius: 8px;
            color: var(--primary-color);
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            min-width: 40px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
            margin: 0 2px;
        }

        .pagination .page-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 82, 204, 0.3);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 82, 204, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            background: #e9ecef;
            color: #adb5bd;
            cursor: not-allowed;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            padding: 0.5rem 1rem;
            font-size: 0 !important;
        }

        /* Show only custom arrows */
        .pagination .page-item:first-child .page-link::after {
            content: '‹';
            font-size: 1.5rem !important;
            line-height: 1;
        }

        .pagination .page-item:last-child .page-link::after {
            content: '›';
            font-size: 1.5rem !important;
            line-height: 1;
        }

        /* Force hide SVG icons */
        .pagination .page-item:first-child .page-link svg,
        .pagination .page-item:last-child .page-link svg {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-film me-2"></i>Cinema
                <span class="tagline">Đặt vé xem phim</span>
            </a>
            
            <!-- Search Bar -->
            <div class="d-flex flex-grow-0 flex-shrink-0 justify-content-center position-relative" style="width: 100%; max-width: 420px;">
                <div class="search-container w-100 position-relative">
                    <input type="text" 
                           class="search-bar" 
                           id="movie-search" 
                           placeholder="🔍 Tìm kiếm phim, rạp chiếu"
                           autocomplete="off">
                    <div id="search-suggestions" class="search-suggestions" style="display: none;"></div>
                </div>
            </div>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('movies.index') }}">Phim đang chiếu</a>
                    </li>
                
                  
                </ul>
                
                <ul class="navbar-nav">
                   
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" id="notification-dropdown">
                                <i class="fas fa-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-badge" style="display: none;"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
                                <li><h6 class="dropdown-header">Thông báo</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <li id="notifications-list">
                                    <div class="text-center text-muted p-3">
                                        <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                        <p class="mb-0">Chưa có thông báo</p>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="#" id="mark-all-read">Đánh dấu tất cả đã đọc</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                                @if(Auth::user()->role === 'admin')
                                    <span class="badge bg-danger ms-1">Admin</span>
                                @elseif(Auth::user()->role === 'staff')
                                    <span class="badge bg-primary ms-1">Staff</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item text-primary fw-bold" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @elseif(Auth::user()->role === 'staff')
                                    <li>
                                        <a class="dropdown-item text-primary fw-bold" href="{{ route('staff.dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>Staff Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('staff.checkin.show') }}">
                                            <i class="fas fa-qrcode me-2"></i>Check-in
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('my-tickets.index') }}"><i class="fas fa-ticket-alt me-2"></i>Vé của tôi</a></li>
                                <li><a class="dropdown-item" href="{{ route('checkin.show') }}"><i class="fas fa-qrcode me-2"></i>Check-in</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Đăng ký</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
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
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-film me-2"></i>Cinema</h5>
                    <p class="text-muted">Hệ thống đặt vé xem phim hiện đại</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-muted"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h6>Phim</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Phim mới</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Phim hot</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Phim sắp chiếu</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6>Thể loại</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Hành động</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Tình cảm</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Kinh dị</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6>Hỗ trợ</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Trung tâm trợ giúp</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Liên hệ</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Điều khoản</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6>Về chúng tôi</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Giới thiệu</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Tuyển dụng</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Báo chí</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2025 Cinema. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="text-muted mb-0">Made with ❤️ in Vietnam</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Navbar background on scroll with smooth transition
        let lastScroll = 0;
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            const currentScroll = window.scrollY;
            
            if (currentScroll > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 4px 20px rgba(15, 23, 42, 0.1)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 4px 20px rgba(15, 23, 42, 0.04)';
            }
            
            lastScroll = currentScroll;
        });

        // Fade in elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all cards and sections
        document.addEventListener('DOMContentLoaded', function() {
            const elementsToAnimate = document.querySelectorAll('.movie-card, .category-card, section');
            elementsToAnimate.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
                observer.observe(el);
            });
        });

        // Search functionality with autocomplete
        const searchBar = document.getElementById('movie-search');
        const suggestionsContainer = document.getElementById('search-suggestions');
        let searchTimeout = null;
        let selectedIndex = -1;
        let suggestions = [];

        if (searchBar && suggestionsContainer) {
            // Handle input for autocomplete
            searchBar.addEventListener('input', function(e) {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    suggestionsContainer.style.display = 'none';
                    suggestions = [];
                    return;
                }

                // Debounce search
                searchTimeout = setTimeout(() => {
                    fetch(`/api/search/autocomplete?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data.length > 0) {
                                suggestions = data.data;
                                renderSuggestions(data.data);
                                suggestionsContainer.style.display = 'block';
                            } else {
                                suggestionsContainer.style.display = 'none';
                                suggestions = [];
                            }
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            suggestionsContainer.style.display = 'none';
                        });
                }, 300);
            });

            // Handle keyboard navigation
            searchBar.addEventListener('keydown', function(e) {
                if (suggestions.length === 0) {
                    if (e.key === 'Enter') {
                        const query = this.value.trim();
                        if (query) {
                            window.location.href = `{{ route('movies.index') }}?search=${encodeURIComponent(query)}`;
                        }
                    }
                    return;
                }

                const items = suggestionsContainer.querySelectorAll('.search-suggestion-item');
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                    updateSelection(items);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    updateSelection(items);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (selectedIndex >= 0 && suggestions[selectedIndex]) {
                        window.location.href = suggestions[selectedIndex].url;
                    } else {
                        const query = this.value.trim();
                        if (query) {
                            window.location.href = `{{ route('movies.index') }}?search=${encodeURIComponent(query)}`;
                        }
                    }
                } else if (e.key === 'Escape') {
                    suggestionsContainer.style.display = 'none';
                    selectedIndex = -1;
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchBar.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                    suggestionsContainer.style.display = 'none';
                }
            });

            // Render suggestions
            function renderSuggestions(data) {
                selectedIndex = -1;
                suggestionsContainer.innerHTML = data.map((movie, index) => {
                    const statusBadge = movie.status === 'NOW_SHOWING' 
                        ? '<span class="badge bg-success search-suggestion-badge">Đang chiếu</span>'
                        : movie.status === 'COMING_SOON'
                        ? '<span class="badge bg-info search-suggestion-badge">Sắp chiếu</span>'
                        : '';
                    
                    const poster = movie.poster_url 
                        ? `<img src="${movie.poster_url}" alt="${movie.title}" class="search-suggestion-poster" onerror="this.style.display='none'">`
                        : '<div class="search-suggestion-poster bg-light d-flex align-items-center justify-content-center"><i class="fas fa-film text-muted"></i></div>';
                    
                    return `
                        <div class="search-suggestion-item" data-index="${index}" data-url="${movie.url}">
                            ${poster}
                            <div class="search-suggestion-info">
                                <div class="search-suggestion-title">${movie.title}</div>
                                <div class="search-suggestion-meta">
                                    <span>${movie.genre}</span>
                                    ${statusBadge}
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

                // Add click handlers
                suggestionsContainer.querySelectorAll('.search-suggestion-item').forEach(item => {
                    item.addEventListener('click', function() {
                        window.location.href = this.dataset.url;
                    });
                });
            }

            // Update selection highlight
            function updateSelection(items) {
                items.forEach((item, index) => {
                    if (index === selectedIndex) {
                        item.classList.add('active');
                        item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                    } else {
                        item.classList.remove('active');
                    }
                });
            }
        }
    </script>

    <!-- Pusher for Realtime Notifications -->
    @auth
    @if(config('broadcasting.default') === 'pusher' && config('broadcasting.connections.pusher.key'))
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        try {
            // Initialize Pusher
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

            // Subscribe to user notifications channel
            const userId = {{ auth()->id() }};
            const notificationsChannel = pusher.subscribe('private-notifications.' + userId);
            
            // Listen for notifications
            notificationsChannel.bind('booking.confirmed', function(data) {
                showNotification('success', data.message, data.booking_code);
                updateNotificationBadge();
                loadNotifications();
            });

            notificationsChannel.bind('payment.success', function(data) {
                showNotification('success', data.message, data.booking_code);
                updateNotificationBadge();
                loadNotifications();
            });

            // Admin notifications
            @if(auth()->user()->role === 'admin')
            const adminChannel = pusher.subscribe('private-admin.notifications');
            adminChannel.bind('booking.confirmed', function(data) {
                showNotification('info', 'Đặt vé mới: ' + data.booking_code, data.booking_code);
                updateNotificationBadge();
                loadNotifications();
            });
            adminChannel.bind('payment.success', function(data) {
                showNotification('info', 'Thanh toán mới: ' + data.booking_code, data.booking_code);
                updateNotificationBadge();
                loadNotifications();
            });
            @endif
        } catch (error) {
            console.error('Pusher initialization error:', error);
        }

        // Notification display function
        function showNotification(type, message, bookingCode) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
            notification.innerHTML = `
                <strong>${type === 'success' ? '✓' : 'ℹ'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);

            // Update notification badge if exists
            updateNotificationBadge();
        }

        function updateNotificationBadge() {
            // Fetch unread notifications count
            fetch('/api/notifications/unread-count')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (badge) {
                        badge.textContent = data.count > 0 ? data.count : '';
                        badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                    }
                })
                .catch(err => console.error('Error fetching notifications:', err));
        }

        // Load notifications
        function loadNotifications() {
            fetch('/api/notifications')
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById('notifications-list');
                    if (data.length === 0) {
                        list.innerHTML = `
                            <div class="text-center text-muted p-3">
                                <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                <p class="mb-0">Chưa có thông báo</p>
                            </div>
                        `;
                    } else {
                        list.innerHTML = data.map(notif => `
                            <a class="dropdown-item ${notif.read_at ? '' : 'bg-light'}" href="${notif.data.booking_id ? '/tickets/booking/' + notif.data.booking_id : '#'}">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1">
                                        <small class="text-muted">${new Date(notif.created_at).toLocaleString('vi-VN')}</small>
                                        <p class="mb-0">${notif.data.message || 'Thông báo mới'}</p>
                                    </div>
                                    ${!notif.read_at ? '<span class="badge bg-primary">Mới</span>' : ''}
                                </div>
                            </a>
                        `).join('');
                    }
                })
                .catch(err => console.error('Error loading notifications:', err));
        }

        // Mark all as read
        document.getElementById('mark-all-read')?.addEventListener('click', function(e) {
            e.preventDefault();
            fetch('/api/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(() => {
                updateNotificationBadge();
                loadNotifications();
            });
        });

        // Update badge on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationBadge();
            loadNotifications();
            
            // Refresh notifications every 30 seconds
            setInterval(() => {
                updateNotificationBadge();
                loadNotifications();
            }, 30000);
        });
    </script>
    @endif
    @endauth

    @yield('scripts')
</body>
</html>
