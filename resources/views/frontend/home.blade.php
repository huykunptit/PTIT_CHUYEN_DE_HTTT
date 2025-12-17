@extends('layouts.app')

@section('title', 'Trang chủ - Cinema')

@push('styles')
<style>
    .movie-card-hover:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
    }
    
    .movie-card-hover:hover .movie-poster {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
@if($featuredMovie)
<section class="hero-section mb-5" style="min-height: 500px; display: flex; align-items: center; position: relative; overflow: hidden; background: #1a1a1a;">
    @if($featuredMovie->poster)
        <img src="{{ $featuredMovie->poster_url }}" 
             alt="{{ $featuredMovie->title }}" 
             style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.4; filter: blur(3px); z-index: 0;">
    @endif
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.5) 50%, rgba(0,0,0,0.3) 100%); z-index: 1;"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <div class="col-md-6 text-white">
                <h1 class="display-4 fw-bold mb-3">{{ $featuredMovie->title }}</h1>
                <p class="lead mb-4">{{ Str::limit($featuredMovie->description, 150) }}</p>
                <div class="d-flex gap-3 mb-4">
                    <span class="badge bg-warning text-dark">IMDb {{ $featuredMovie->rating }}</span>
                    <span class="badge bg-light text-dark">{{ $featuredMovie->genre }}</span>
                    <span class="badge bg-light text-dark">{{ $featuredMovie->release_date->format('Y') }}</span>
                </div>
                <a href="{{ route('movies.show', $featuredMovie) }}" class="btn btn-light btn-lg">
                    <i class="fas fa-ticket-alt me-2"></i>Đặt vé ngay
                </a>
            </div>
        </div>
    </div>
</section>
@endif

<div class="container">
    <!-- Phim đang chiếu -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Phim đang chiếu</h2>
            <a href="{{ route('movies.index') }}" class="text-decoration-none">
                Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @forelse($nowShowingMovies as $movie)
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm h-100 movie-card-hover">
                    <div class="position-relative" style="height: 250px; overflow: hidden; background: #e0e0e0;">
                        @if($movie->poster && $movie->poster_url)
                            <img src="{{ $movie->poster_url }}" 
                                 alt="{{ $movie->title }}" 
                                 class="card-img-top movie-poster" 
                                 style="height: 100%; width: 100%; object-fit: cover; transition: transform 0.3s ease; position: absolute; top: 0; left: 0;"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        @endif
                        <div class="d-flex align-items-center justify-content-center h-100" style="display: {{ ($movie->poster && $movie->poster_url) ? 'none' : 'flex' }};">
                            <i class="fas fa-film text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title mb-2">{{ Str::limit($movie->title, 30) }}</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ $movie->genre }}</small>
                            <span class="badge bg-warning text-dark">{{ $movie->rating }}</span>
                        </div>
                        <a href="{{ route('movies.show', $movie) }}" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-ticket-alt me-1"></i>Đặt vé
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-film fa-3x text-muted mb-3"></i>
                <p class="text-muted">Chưa có phim đang chiếu</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Voucher/Promotion Section -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Voucher & Khuyến mãi</h2>
        </div>
        <div class="row g-4">
            @forelse($promotions as $promotion)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">{{ $promotion->name }}</h5>
                                <span class="badge bg-light text-dark">{{ $promotion->code }}</span>
                            </div>
                            <i class="fas fa-ticket-alt fa-2x opacity-50"></i>
                        </div>
                        <p class="card-text mb-3">{{ Str::limit($promotion->description, 100) }}</p>
                        <div class="mb-3">
                            @if($promotion->type === 'PERCENTAGE')
                                <span class="badge bg-warning text-dark fs-6">
                                    Giảm {{ $promotion->value }}%
                                </span>
                            @elseif($promotion->type === 'FIXED_AMOUNT')
                                <span class="badge bg-warning text-dark fs-6">
                                    Giảm {{ number_format($promotion->value, 0, ',', '.') }}₫
                                </span>
                            @else
                                <span class="badge bg-warning text-dark fs-6">
                                    {{ $promotion->name }}
                                </span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="opacity-75">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $promotion->start_date->format('d/m') }} - {{ $promotion->end_date->format('d/m/Y') }}
                            </small>
                            @if($promotion->min_amount)
                                <small class="opacity-75">
                                    Đơn tối thiểu: {{ number_format($promotion->min_amount, 0, ',', '.') }}₫
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted">Hiện tại chưa có voucher khuyến mãi</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Phim sắp chiếu -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Phim sắp chiếu</h2>
            <a href="{{ route('movies.index') }}?status=COMING_SOON" class="text-decoration-none">
                Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @forelse($comingSoonMovies as $movie)
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm h-100 movie-card-hover">
                    <div class="position-relative" style="height: 250px; overflow: hidden; background: #e0e0e0;">
                        @if($movie->poster && $movie->poster_url)
                            <img src="{{ $movie->poster_url }}" 
                                 alt="{{ $movie->title }}" 
                                 class="card-img-top movie-poster" 
                                 style="height: 100%; width: 100%; object-fit: cover; transition: transform 0.3s ease; position: absolute; top: 0; left: 0;"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        @endif
                        <div class="d-flex align-items-center justify-content-center h-100" style="display: {{ ($movie->poster && $movie->poster_url) ? 'none' : 'flex' }};">
                            <i class="fas fa-film text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title mb-2">{{ Str::limit($movie->title, 30) }}</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ $movie->genre }}</small>
                            <span class="badge bg-info">{{ $movie->release_date->format('d/m') }}</span>
                        </div>
                        <span class="badge bg-info w-100">Sắp chiếu</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-film fa-3x text-muted mb-3"></i>
                <p class="text-muted">Chưa có phim sắp chiếu</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Phim chưa chiếu -->
    @if(isset($endedMovies) && $endedMovies->count() > 0)
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Phim chưa chiếu</h2>
            <a href="{{ route('movies.index') }}?status=ENDED" class="text-decoration-none">
                Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($endedMovies as $movie)
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm h-100 movie-card-hover">
                    <div class="position-relative" style="height: 250px; overflow: hidden; background: #e0e0e0;">
                        @if($movie->poster && $movie->poster_url)
                            <img src="{{ $movie->poster_url }}" 
                                 alt="{{ $movie->title }}" 
                                 class="card-img-top movie-poster" 
                                 style="height: 100%; width: 100%; object-fit: cover; transition: transform 0.3s ease; position: absolute; top: 0; left: 0;"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        @endif
                        <div class="d-flex align-items-center justify-content-center h-100" style="display: {{ ($movie->poster && $movie->poster_url) ? 'none' : 'flex' }};">
                            <i class="fas fa-film text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title mb-2">{{ Str::limit($movie->title, 30) }}</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ $movie->genre }}</small>
                            <span class="badge bg-secondary">{{ $movie->release_date->format('Y') }}</span>
                        </div>
                        <span class="badge bg-secondary w-100">Đã kết thúc</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
