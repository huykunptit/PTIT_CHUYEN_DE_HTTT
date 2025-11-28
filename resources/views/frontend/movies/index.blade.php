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
            @if(request('search'))
                <div class="alert alert-info text-center">
                    <i class="fas fa-search me-2"></i>
                    Kết quả tìm kiếm cho: <strong>"{{ request('search') }}"</strong>
                    <span class="badge bg-primary ms-2">{{ $movies->total() }} phim</span>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('movies.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-3">
                                <label for="search" class="form-label">Tìm kiếm</label>
                                <input type="text" name="search" id="search" class="form-control" 
                                       placeholder="Tìm kiếm phim theo tên, thể loại..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="NOW_SHOWING" {{ request('status') == 'NOW_SHOWING' ? 'selected' : '' }}>Đang chiếu</option>
                                    <option value="COMING_SOON" {{ request('status') == 'COMING_SOON' ? 'selected' : '' }}>Sắp chiếu</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="genre" class="form-label">Thể loại</label>
                                <input type="text" name="genre" id="genre" class="form-control" 
                                       placeholder="Nhập thể loại..." value="{{ request('genre') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Tìm kiếm
                                </button>
                            </div>
                            <div class="col-md-2 mb-3">
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
    
    <!-- Movies Info -->
    @if($movies->total() > 0)
    <div class="row mb-3">
        <div class="col-md-6">
            <p class="text-muted">
                <i class="fas fa-info-circle me-2"></i>
                Hiển thị <strong>{{ $movies->firstItem() }}</strong> đến <strong>{{ $movies->lastItem() }}</strong> 
                trong tổng số <strong>{{ $movies->total() }}</strong> phim
            </p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <a href="{{ $movies->appends(request()->except('page'))->url(1) }}" 
                   class="btn btn-sm btn-outline-primary {{ $movies->currentPage() == 1 ? 'active' : '' }}">
                    Trang đầu
                </a>
                <a href="{{ $movies->appends(request()->except('page'))->url($movies->lastPage()) }}" 
                   class="btn btn-sm btn-outline-primary {{ $movies->currentPage() == $movies->lastPage() ? 'active' : '' }}">
                    Trang cuối
                </a>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Movies Grid -->
    <div class="row">
        @forelse($movies as $movie)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card movie-card h-100 movie-card-hover">
                <div class="position-relative" style="height: 300px; overflow: hidden;">
                    @if($movie->poster_url)
                        <img src="{{ $movie->poster_url }}" 
                             alt="{{ $movie->title }}" 
                             class="card-img-top movie-poster" 
                             style="height: 100%; width: 100%; object-fit: cover; transition: transform 0.3s ease;"
                             loading="lazy"
                             onerror="this.onerror=null; this.parentElement.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)'; this.style.display='none'; this.parentElement.innerHTML='<div class=\'d-flex align-items-center justify-content-center h-100\'><i class=\'fas fa-film text-white\' style=\'font-size: 3rem; opacity: 0.5;\'></i></div>';">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-film text-white" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="position-absolute top-0 start-0 m-2">
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
                    </div>
                    
                    <!-- Age Rating -->
                    <div class="position-absolute top-0 end-0 m-2">
                        @if($movie->age_rating > 0)
                            <span class="badge bg-warning">{{ $movie->age_rating }}+</span>
                        @else
                            <span class="badge bg-success">P</span>
                        @endif
                    </div>
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
                </div>
                <div class="card-footer bg-transparent">
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
    
    <!-- Custom Pagination -->
    @if($movies->hasPages())
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Movie pagination">
            <ul class="pagination pagination-lg">
                {{-- First Page Link --}}
                @if($movies->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-angle-double-left"></i></span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $movies->appends(request()->except('page'))->url(1) }}">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                    </li>
                @endif
                
                {{-- Previous Page Link --}}
                @if($movies->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-angle-left"></i> Trước</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $movies->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}">
                            <i class="fas fa-angle-left"></i> Trước
                        </a>
                    </li>
                @endif
                
                {{-- Pagination Elements --}}
                @php
                    $currentPage = $movies->currentPage();
                    $lastPage = $movies->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                    
                    // Always show first page
                    if($start > 1) {
                        $start = 1;
                    }
                    
                    // Adjust to always show 5 pages if possible
                    if($end - $start < 4) {
                        if($start == 1) {
                            $end = min($lastPage, 5);
                        } else {
                            $start = max(1, $end - 4);
                        }
                    }
                @endphp
                
                {{-- Show dots if not starting from page 1 --}}
                @if($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $movies->appends(request()->except('page'))->url(1) }}">1</a>
                    </li>
                    @if($start > 2)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                @endif
                
                {{-- Page Numbers --}}
                @for($page = $start; $page <= $end; $page++)
                    @if($page == $currentPage)
                        <li class="page-item active">
                            <span class="page-link">
                                {{ $page }}
                                <span class="visually-hidden">(current)</span>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $movies->appends(request()->except('page'))->url($page) }}">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endfor
                
                {{-- Show dots if not ending at last page --}}
                @if($end < $lastPage)
                    @if($end < $lastPage - 1)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="{{ $movies->appends(request()->except('page'))->url($lastPage) }}">
                            {{ $lastPage }}
                        </a>
                    </li>
                @endif
                
                {{-- Next Page Link --}}
                @if($movies->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $movies->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}">
                            Sau <i class="fas fa-angle-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Sau <i class="fas fa-angle-right"></i></span>
                    </li>
                @endif
                
                {{-- Last Page Link --}}
                @if($movies->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $movies->appends(request()->except('page'))->url($lastPage) }}">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-angle-double-right"></i></span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    
    <!-- Page Jump -->
    <div class="text-center mt-3">
        <form method="GET" action="{{ route('movies.index') }}" class="d-inline-flex align-items-center gap-2">
            @foreach(request()->except('page') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <label for="page-jump" class="text-muted mb-0">Nhảy đến trang:</label>
            <input type="number" name="page" id="page-jump" class="form-control form-control-sm" 
                   style="width: 80px;" min="1" max="{{ $movies->lastPage() }}" 
                   placeholder="{{ $movies->currentPage() }}">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>
    </div>
    @endif
</div>

<style>
.movie-card-hover {
    transition: all 0.3s ease;
}

.movie-card-hover:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.movie-card-hover:hover .movie-poster {
    transform: scale(1.1);
}

.pagination {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 50px;
    overflow: hidden;
}

.pagination .page-item:first-child .page-link {
    border-top-left-radius: 50px;
    border-bottom-left-radius: 50px;
}

.pagination .page-item:last-child .page-link {
    border-top-right-radius: 50px;
    border-bottom-right-radius: 50px;
}

.pagination .page-link {
    color: #667eea;
    border: none;
    padding: 12px 18px;
    margin: 0 2px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.pagination .page-link:hover {
    background-color: #667eea;
    color: white;
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transform: scale(1.1);
}

.pagination .page-item.disabled .page-link {
    background-color: #f8f9fa;
    color: #6c757d;
}

#page-jump:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>
@endsection