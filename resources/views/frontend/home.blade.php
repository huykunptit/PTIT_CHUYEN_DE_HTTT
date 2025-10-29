@extends('layouts.app')

@section('title', 'Trang chủ - Cinema')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="background-image: url('https://images.unsplash.com/photo-1489599808082-3b4a0b5a0b5a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="movie-title">Avatar: The Way of Water</h1>
            <p class="movie-subtitle">Đang chiếu tại rạp</p>
            
            <div class="movie-meta">
                <span class="meta-badge imdb-badge">IMDb 8.5</span>
                <span class="meta-badge">192 phút</span>
                <span class="meta-badge">2024</span>
                <span class="meta-badge">13+</span>
            </div>
            
            <div class="movie-genres">
                <span class="genre-tag">Sci-Fi</span>
                <span class="genre-tag">Action</span>
                <span class="genre-tag">Adventure</span>
                <span class="genre-tag">IMAX</span>
                <span class="genre-tag">3D</span>
            </div>
            
            <p class="mb-4">Jake Sully và gia đình của anh ấy khám phá những vùng biển của Pandora, gặp gỡ những sinh vật biển kỳ lạ và khám phá những bí mật của hành tinh này.</p>
            
            <div class="hero-actions">
                <a href="{{ route('movies.index') }}" class="play-btn">
                    <i class="fas fa-ticket-alt"></i>
                </a>
                <button class="action-btn">
                    <i class="fas fa-heart"></i>
                </button>
                <button class="action-btn">
                    <i class="fas fa-share"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <h2 class="section-title">Trải nghiệm điện ảnh</h2>
        <div class="categories-grid">
            <div class="category-card" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                <h3>IMAX</h3>
                <a href="#">Đặt vé IMAX ></a>
            </div>
            <div class="category-card" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                <h3>4DX</h3>
                <a href="#">Đặt vé 4DX ></a>
            </div>
            <div class="category-card" style="background: linear-gradient(135deg, #10b981, #059669);">
                <h3>VIP</h3>
                <a href="#">Ghế VIP ></a>
            </div>
            <div class="category-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <h3>3D</h3>
                <a href="#">Phim 3D ></a>
            </div>
            <div class="category-card" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <h3>Combo</h3>
                <a href="#">Combo bắp nước ></a>
            </div>
            <div class="category-card" style="background: linear-gradient(135deg, #f97316, #ea580c);">
                <h3>Khuyến mãi</h3>
                <a href="#">Ưu đãi hôm nay ></a>
            </div>
            <div class="category-card" style="background: linear-gradient(135deg, #6b7280, #4b5563);">
                <h3>Rạp gần bạn</h3>
                <a href="#">Tìm rạp ></a>
            </div>
        </div>
    </div>
</section>

<!-- Movie Rows -->
<section class="movie-rows">
    <div class="container">
        <!-- Phim đang chiếu -->
        <div class="movie-row">
            <div class="row-title">
                <h3>Phim đang chiếu</h3>
                <a href="{{ route('movies.index') }}">Xem tất cả ></a>
            </div>
            <div class="movies-scroll">
                @forelse($featuredMovies as $movie)
                <div class="movie-card">
                    <div class="movie-poster">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="movie-info">
                        <h4>{{ $movie->title }}</h4>
                        <div class="movie-badges">
                            <span class="badge imdb-badge">IMDb {{ $movie->rating }}</span>
                            <span class="badge year-badge">{{ $movie->release_date->year }}</span>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('movies.show', $movie) }}" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-ticket-alt me-1"></i>Đặt vé
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                
                {{-- Hiển thị dữ liệu từ API nếu có --}}
                @if($apiMovies && isset($apiMovies['results']))
                    @foreach(array_slice($apiMovies['results'], 0, 6) as $apiMovie)
                    <div class="movie-card">
                        <div class="movie-poster">
                            @if(isset($apiMovie['poster_path']))
                                <img src="https://image.tmdb.org/t/p/w300{{ $apiMovie['poster_path'] }}" 
                                     alt="{{ $apiMovie['title'] }}" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="fas fa-film"></i>
                            @endif
                        </div>
                        <div class="movie-info">
                            <h4>{{ $apiMovie['title'] ?? 'N/A' }}</h4>
                            <div class="movie-badges">
                                <span class="badge imdb-badge">IMDb {{ number_format($apiMovie['vote_average'] ?? 0, 1) }}</span>
                                <span class="badge year-badge">{{ date('Y', strtotime($apiMovie['release_date'] ?? 'now')) }}</span>
                            </div>
                            <div class="mt-2">
                                <a href="#" class="btn btn-sm btn-primary w-100">
                                    <i class="fas fa-ticket-alt me-1"></i>Đặt vé
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="movie-card">
                    <div class="movie-poster">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="movie-info">
                        <h4>Avatar: The Way of Water</h4>
                        <div class="movie-badges">
                            <span class="badge imdb-badge">IMDb 8.5</span>
                            <span class="badge year-badge">2024</span>
                        </div>
                        <div class="mt-2">
                            <a href="#" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-ticket-alt me-1"></i>Đặt vé
                            </a>
                        </div>
                    </div>
                </div>
                <div class="movie-card">
                    <div class="movie-poster">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="movie-info">
                        <h4>Black Panther: Wakanda Forever</h4>
                        <div class="movie-badges">
                            <span class="badge imdb-badge">IMDb 8.2</span>
                            <span class="badge year-badge">2024</span>
                        </div>
                        <div class="mt-2">
                            <a href="#" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-ticket-alt me-1"></i>Đặt vé
                            </a>
                        </div>
                    </div>
                </div>
                <div class="movie-card">
                    <div class="movie-poster">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="movie-info">
                        <h4>Top Gun: Maverick</h4>
                        <div class="movie-badges">
                            <span class="badge imdb-badge">IMDb 8.8</span>
                            <span class="badge year-badge">2024</span>
                        </div>
                        <div class="mt-2">
                            <a href="#" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-ticket-alt me-1"></i>Đặt vé
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @endforelse
            </div>
        </div>

        <!-- Phim sắp chiếu -->
        <div class="movie-row">
            <div class="row-title">
                <h3>Phim sắp chiếu</h3>
                <a href="#">Xem tất cả ></a>
            </div>
            <div class="movies-scroll">
                @forelse($comingSoonMovies as $movie)
                <div class="movie-card">
                    <div class="movie-poster">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="movie-info">
                        <h4>{{ $movie->title }}</h4>
                        <div class="movie-badges">
                            <span class="badge imdb-badge">IMDb {{ $movie->rating }}</span>
                            <span class="badge year-badge">{{ $movie->release_date->year }}</span>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-info">Sắp chiếu</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="movie-card">
                    <div class="movie-poster">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="movie-info">
                        <h4>Spider-Man: Across the Spider-Verse</h4>
                        <div class="movie-badges">
                            <span class="badge imdb-badge">IMDb 8.7</span>
                            <span class="badge year-badge">2024</span>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-info">Sắp chiếu</span>
                        </div>
                    </div>
                </div>
                <div class="movie-card">
                    <div class="movie-poster">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="movie-info">
                        <h4>Fast X</h4>
                        <div class="movie-badges">
                            <span class="badge imdb-badge">IMDb 7.2</span>
                            <span class="badge year-badge">2024</span>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-info">Sắp chiếu</span>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Phim nổi bật -->
        <div class="movie-row">
            <div class="row-title">
                <h3>Phim nổi bật</h3>
                <a href="#">Xem tất cả ></a>
            </div>
            <div class="movies-scroll">
                <div class="movie-card">
                    <div class="movie-poster">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="movie-info">
                        <h4>Dune: Part Two</h4>
                        <div class="movie-badges">
                            <span class="badge imdb-badge">IMDb 9.1</span>
                            <span class="badge year-badge">2024</span>
                        </div>
                        <div class="mt-2">
                            <a href="#" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-ticket-alt me-1"></i>Đặt vé
                            </a>
                        </div>
                    </div>
                </div>
                <div class="movie-card">
                    <div class="movie-poster">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="movie-info">
                        <h4>Oppenheimer</h4>
                        <div class="movie-badges">
                            <span class="badge imdb-badge">IMDb 8.9</span>
                            <span class="badge year-badge">2024</span>
                        </div>
                        <div class="mt-2">
                            <a href="#" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-ticket-alt me-1"></i>Đặt vé
                            </a>
                        </div>
                    </div>
                </div>
                <div class="movie-card">
                    <div class="movie-poster">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="movie-info">
                        <h4>Barbie</h4>
                        <div class="movie-badges">
                            <span class="badge imdb-badge">IMDb 8.3</span>
                            <span class="badge year-badge">2024</span>
                        </div>
                        <div class="mt-2">
                            <a href="#" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-ticket-alt me-1"></i>Đặt vé
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
