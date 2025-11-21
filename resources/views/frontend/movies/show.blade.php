@extends('layouts.app')

@section('title', $movie->title . ' - Cinema')

@section('content')
<div class="container py-5">
    <!-- Movie Header -->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="d-flex align-items-center justify-content-center shadow-sm"
                 style="height: 400px; border-radius: 16px; background: linear-gradient(135deg, #eef2ff 0%, #e0f2fe 100%);">
                <i class="fas fa-film text-primary" style="font-size: 4rem;"></i>
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
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2 text-primary"></i>Lịch chiếu
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Filter by Cinema and Date (Chọn rạp/ngày) -->
                    <form method="GET" action="{{ route('movies.show', $movie) }}" class="mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="city" class="form-label text-muted">
                                    <i class="fas fa-map-marker-alt me-2"></i>Khu vực
                                </label>
                                <select class="form-select" id="city" name="city">
                                    <option value="">Tất cả khu vực</option>
                                    <option value="HCM" {{ $selectedCity === 'HCM' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                                    <option value="HN" {{ $selectedCity === 'HN' ? 'selected' : '' }}>Hà Nội</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="cinema_id" class="form-label text-muted">
                                    <i class="fas fa-building me-2"></i>Chọn rạp
                                </label>
                                <select class="form-select" id="cinema_id" name="cinema_id">
                                    <option value="">Tất cả rạp</option>
                                    @foreach($cinemas as $cinema)
                                        <option value="{{ $cinema->id }}" {{ $selectedCinemaId == $cinema->id ? 'selected' : '' }}>
                                            {{ $cinema->name }} ({{ $cinema->city === 'HN' ? 'Hà Nội' : 'TP.HCM' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date" class="form-label text-muted">
                                    <i class="fas fa-calendar me-2"></i>Chọn ngày
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="date" 
                                       name="date" 
                                       value="{{ $selectedDate ?? '' }}"
                                       min="{{ now()->toDateString() }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100 me-2">
                                    <i class="fas fa-search me-2"></i>Tìm kiếm
                                </button>
                                <a href="{{ route('movies.show', $movie) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    @if($showtimes->count() > 0)
                        @foreach($showtimes as $date => $showtimeGroup)
                            <div class="mb-4">
                                <h6 class="text-primary mb-3 fw-semibold">
                                    <i class="fas fa-calendar me-2"></i>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($date)->locale('vi')->dayName }}
                                </h6>
                                
                                    @foreach($showtimeGroup->groupBy(function($st) { return $st->room->cinema_id; }) as $cinemaId => $cinemaShowtimes)
                                    @php $cinemaInfo = $cinemaShowtimes->first()->room->cinema; @endphp
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2 fw-semibold d-flex align-items-center justify-content-between">
                                            <span>
                                                <i class="fas fa-building me-2 text-secondary"></i>
                                                {{ $cinemaInfo->name }}
                                            </span>
                                            <span class="badge {{ $cinemaInfo->city === 'HN' ? 'bg-info text-dark' : 'bg-primary' }}">
                                                {{ $cinemaInfo->city === 'HN' ? 'Hà Nội' : 'TP. Hồ Chí Minh' }}
                                            </span>
                                        </h6>
                                        <div class="row">
                                            @foreach($cinemaShowtimes as $showtime)
                                            <div class="col-md-3 mb-3">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body text-center">
                                                        <p class="text-muted mb-2 small">
                                                            <i class="fas fa-door-open me-1 text-secondary"></i>{{ $showtime->room->name }}
                                                        </p>
                                                        <p class="text-primary fw-bold mb-3 fs-5">
                                                            <i class="fas fa-clock me-2"></i>
                                                            {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} - 
                                                            {{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}
                                                        </p>
                                                        @if($showtime->status == 'ACTIVE')
                                                            <a href="{{ route('booking.create', $showtime) }}" 
                                                               class="btn btn-outline-primary btn-sm">
                                                                <i class="fas fa-ticket-alt me-2"></i>Chọn suất
                                                            </a>
                                                        @else
                                                            <span class="badge bg-light text-secondary border">Đã hủy</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-clock text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">Chưa có lịch chiếu</h5>
                        <p class="text-muted">Hãy quay lại sau để xem lịch chiếu mới nhất!</p>
                        <a href="{{ route('movies.index') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-arrow-left me-2"></i>Về danh sách phim
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
