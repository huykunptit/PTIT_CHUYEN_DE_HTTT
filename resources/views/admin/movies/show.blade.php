@extends('layouts.admin')

@section('title', 'Chi tiết phim - Admin Panel')
@section('page-title', 'Chi tiết phim')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Thông tin phim</h5>
                <div>
                    <a href="{{ route('admin.movies.edit', $movie) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Sửa
                    </a>
                    <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="bg-dark d-flex align-items-center justify-content-center" 
                             style="height: 300px; border-radius: 8px;">
                            <i class="fas fa-film text-light" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h3 class="mb-3">{{ $movie->title }}</h3>
                        
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>Thể loại:</strong> {{ $movie->genre }}
                            </div>
                            <div class="col-sm-6">
                                <strong>Thời lượng:</strong> {{ $movie->duration }} phút
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>Quốc gia:</strong> {{ $movie->country }}
                            </div>
                            <div class="col-sm-6">
                                <strong>Ngôn ngữ:</strong> {{ $movie->language }}
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>Giới hạn độ tuổi:</strong> 
                                @if($movie->age_rating == 0)
                                    P - Mọi lứa tuổi
                                @else
                                    {{ $movie->age_rating }}+
                                @endif
                            </div>
                            <div class="col-sm-6">
                                <strong>Đánh giá:</strong> 
                                <div class="text-warning d-inline">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $movie->rating/2 ? '' : '-o' }}"></i>
                                    @endfor
                                    {{ $movie->rating }}/10
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>Ngày phát hành:</strong> {{ $movie->release_date->format('d/m/Y') }}
                            </div>
                            <div class="col-sm-6">
                                <strong>Ngày kết thúc:</strong> {{ $movie->end_date->format('d/m/Y') }}
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>Trạng thái:</strong> 
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
                            <div class="col-sm-6">
                                <strong>Phim nổi bật:</strong> 
                                @if($movie->is_featured)
                                    <span class="badge bg-warning">Có</span>
                                @else
                                    <span class="badge bg-secondary">Không</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($movie->trailer_url)
                        <div class="mb-3">
                            <strong>Trailer:</strong> 
                            <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-play me-1"></i>Xem trailer
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5>Mô tả phim</h5>
                    <p class="text-muted">{{ $movie->description }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Thống kê</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <i class="fas fa-chart-bar text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Chưa có dữ liệu thống kê</p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Lịch chiếu</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <i class="fas fa-clock text-muted" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2">Chưa có lịch chiếu</p>
                    <a href="{{ route('admin.showtimes.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Tạo lịch chiếu
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
