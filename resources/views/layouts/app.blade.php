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
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
            --dark-bg: #0a0a0a;
            --card-bg: #1a1a1a;
            --text-primary: #ffffff;
            --text-secondary: #cccccc;
            --accent-color: #ffd700;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header Styles */
        .navbar {
            background: rgba(10, 10, 10, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.8rem;
            color: var(--primary-color) !important;
        }

        .navbar-brand .tagline {
            font-size: 0.7rem;
            color: var(--text-secondary);
            display: block;
            margin-top: -5px;
        }

        .search-bar {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            color: white;
            padding: 10px 20px;
            width: 300px;
        }

        .search-bar::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .search-bar:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
            color: white;
        }

        .nav-link {
            color: var(--text-primary) !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
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
            color: var(--accent-color);
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        }

        .movie-subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }

        .movie-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .meta-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .imdb-badge {
            background: var(--accent-color);
            color: var(--dark-bg);
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
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        /* Categories Section */
        .categories-section {
            padding: 4rem 0;
            background: var(--dark-bg);
        }

        .section-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
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
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .category-card:hover::before {
            opacity: 1;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
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

        .movie-card {
            min-width: 200px;
            background: var(--card-bg);
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .movie-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }

        .movie-poster {
            width: 100%;
            height: 280px;
            object-fit: cover;
            background: var(--card-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 3rem;
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
            background: rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
        }

        /* Footer */
        .footer {
            background: var(--card-bg);
            padding: 3rem 0 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
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
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-film me-2"></i>Cinema
                <span class="tagline">Đặt vé xem phim</span>
            </a>
            
            <!-- Search Bar -->
            <div class="d-flex flex-grow-1 justify-content-center">
                <input type="text" class="search-bar" placeholder="🔍 Tìm kiếm phim, rạp chiếu">
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
                    <li class="nav-item">
                        <a class="nav-link" href="#">Phim sắp chiếu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Rạp chiếu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Khuyến mãi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tin tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="#">Đặt vé ngay</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tải ứng dụng Cinema</a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-ticket-alt me-2"></i>Vé của tôi</a></li>
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

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(10, 10, 10, 0.98)';
            } else {
                navbar.style.background = 'rgba(10, 10, 10, 0.95)';
            }
        });

        // Search functionality
        const searchBar = document.querySelector('.search-bar');
        if (searchBar) {
            searchBar.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const query = this.value.trim();
                    if (query) {
                        window.location.href = `{{ route('movies.index') }}?search=${encodeURIComponent(query)}`;
                    }
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
