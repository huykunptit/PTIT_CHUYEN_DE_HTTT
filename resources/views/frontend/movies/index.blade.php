@extends('layouts.app')

@section('title', 'Danh sách phim - Cinema')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-center mb-4">
                <i class="fas fa-film me-2"></i>Danh sách phim
            </h2>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('movies.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-3 mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="NOW_SHOWING" {{ request('status') == 'NOW_SHOWING' ? 'selected' : '' }}>Đang chiếu</option>
                                    <option value="COMING_SOON" {{ request('status') == 'COMING_SOON' ? 'selected' : '' }}>Sắp chiếu</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="genre" class="form-label">Thể loại</label>
                                <input type="text" name="genre" id="genre" class="form-control" 
                                       placeholder="Nhập thể loại..." value="{{ request('genre') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Tìm kiếm
                                </button>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('movies.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-refresh me-2"></i>Làm mới
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Movies Grid -->
    <div class="row">
        @forelse($movies as $movie)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card movie-card h-100">
                <div class="card-img-top bg-dark d-flex align-items-center justify-content-center" 
                     style="height: 300px;">
                    <i class="fas fa-film text-light" style="font-size: 3rem;"></i>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $movie->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($movie->description, 100) }}</p>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>{{ $movie->duration }} phút
                            </small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>{{ $movie->release_date->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary">{{ $movie->genre }}</span>
                        <div class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $movie->rating/2 ? '' : '-o' }}"></i>
                            @endfor
                            <span class="ms-1">{{ $movie->rating }}</span>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        @switch($movie->status)
                            @case('NOW_SHOWING')
                                <span class="badge bg-success">Đang chiếu</span>
                                @break
                            @case('COMING_SOON')
                                <span class="badge bg-info">Sắp chiếu</span>
                                @break
                            @case('ENDED')
                                <span class="badge bg-secondary">Kết thúc</span>
                                @break
                        @endswitch
                        
                        @if($movie->age_rating > 0)
                            <span class="badge bg-warning">{{ $movie->age_rating }}+</span>
                        @else
                            <span class="badge bg-success">P</span>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('movies.show', $movie) }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-eye me-2"></i>Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <div class="py-5">
                <i class="fas fa-film text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">Không tìm thấy phim nào</h4>
                <p class="text-muted">Hãy thử thay đổi bộ lọc tìm kiếm</p>
                <a href="{{ route('movies.index') }}" class="btn btn-primary">
                    <i class="fas fa-refresh me-2"></i>Xem tất cả phim
                </a>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($movies->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $movies->links() }}
    </div>
    @endif
</div>
@endsection
