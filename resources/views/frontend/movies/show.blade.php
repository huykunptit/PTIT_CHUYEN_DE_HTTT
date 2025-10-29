@extends('layouts.app')

@section('title', $movie->title . ' - Cinema')

@section('content')
<div class="container py-5">
    <!-- Movie Header -->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="bg-dark d-flex align-items-center justify-content-center" 
                 style="height: 400px; border-radius: 8px;">
                <i class="fas fa-film text-light" style="font-size: 4rem;"></i>
            </div>
        </div>
        <div class="col-md-8">
            <h1 class="mb-3">{{ $movie->title }}</h1>
            
            <div class="row mb-3">
                <div class="col-sm-6">
                    <p><strong>Thể loại:</strong> {{ $movie->genre }}</p>
                    <p><strong>Thời lượng:</strong> {{ $movie->duration }} phút</p>
                    <p><strong>Quốc gia:</strong> {{ $movie->country }}</p>
                </div>
                <div class="col-sm-6">
                    <p><strong>Ngôn ngữ:</strong> {{ $movie->language }}</p>
                    <p><strong>Ngày phát hành:</strong> {{ $movie->release_date->format('d/m/Y') }}</p>
                    <p><strong>Giới hạn độ tuổi:</strong> 
                        @if($movie->age_rating == 0)
                            P - Mọi lứa tuổi
                        @else
                            {{ $movie->age_rating }}+
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="d-flex align-items-center mb-3">
                <div class="text-warning me-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= $movie->rating/2 ? '' : '-o' }}"></i>
                    @endfor
                    <span class="ms-2">{{ $movie->rating }}/10</span>
                </div>
                @switch($movie->status)
                    @case('NOW_SHOWING')
                        <span class="badge bg-success fs-6">Đang chiếu</span>
                        @break
                    @case('COMING_SOON')
                        <span class="badge bg-info fs-6">Sắp chiếu</span>
                        @break
                    @case('ENDED')
                        <span class="badge bg-secondary fs-6">Kết thúc</span>
                        @break
                @endswitch
            </div>
            
            @if($movie->trailer_url)
            <div class="mb-3">
                <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-danger">
                    <i class="fas fa-play me-2"></i>Xem trailer
                </a>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Movie Description -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Mô tả phim</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $movie->description }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Showtimes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Lịch chiếu
                    </h5>
                </div>
                <div class="card-body">
                    @if($showtimes->count() > 0)
                        @foreach($showtimes as $date => $showtimeGroup)
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-calendar me-2"></i>{{ \Carbon\Carbon::parse($date)->format('d/m/Y - l') }}
                            </h6>
                            <div class="row">
                                @foreach($showtimeGroup as $showtime)
                                <div class="col-md-3 mb-3">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">{{ $showtime->room->cinema->name }}</h6>
                                            <p class="text-muted mb-2">{{ $showtime->room->name }}</p>
                                            <p class="text-primary fw-bold mb-3">
                                                {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}
                                            </p>
                                            @if($showtime->status == 'ACTIVE')
                                                <a href="{{ route('booking.create', $showtime) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-ticket-alt me-1"></i>Đặt vé
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">Đã hủy</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-clock text-muted" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mt-3">Chưa có lịch chiếu</h5>
                        <p class="text-muted">Hãy quay lại sau để xem lịch chiếu mới nhất!</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
