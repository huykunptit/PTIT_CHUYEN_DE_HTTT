@extends('layouts.app')

@section('title', 'Vé của tôi - Cinema')

@section('content')
<div class="container py-5" style="min-height: 70vh;">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">
                <i class="fas fa-ticket-alt me-2"></i>Vé của tôi
            </h2>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Tổng số vé</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                        </div>
                        <i class="fas fa-ticket-alt fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Đã xác nhận</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['confirmed'] }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Chờ xử lý</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['pending'] }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Đã hủy</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['cancelled'] }}</h3>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('my-tickets.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Lọc theo trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="CONFIRMED" {{ request('status') == 'CONFIRMED' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-2"></i>Lọc
                    </button>
                    <a href="{{ route('my-tickets.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings List -->
    @forelse($bookings as $booking)
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-start mb-3">
                        @if($booking->showtime->movie->poster_url)
                            <img src="{{ $booking->showtime->movie->poster_url }}" alt="{{ $booking->showtime->movie->title }}" 
                                 class="me-3" style="width: 80px; height: 120px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div class="me-3 bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 120px; border-radius: 8px;">
                                <i class="fas fa-film text-white fa-2x"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h5 class="mb-2">{{ $booking->showtime->movie->title }}</h5>
                            <p class="text-muted mb-1">
                                <i class="fas fa-building me-2"></i>{{ $booking->showtime->room->cinema->name }}
                            </p>
                            <p class="text-muted mb-1">
                                <i class="fas fa-calendar me-2"></i>{{ $booking->showtime->date->format('d/m/Y') }}
                                <i class="fas fa-clock ms-3 me-2"></i>{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-ticket-alt me-2"></i>{{ $booking->tickets->count() }} vé
                                <span class="ms-3">
                                    <strong class="text-primary">{{ number_format($booking->final_amount, 0, ',', '.') }}₫</strong>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="mb-3">
                        <span class="badge bg-{{ $booking->status === 'CONFIRMED' ? 'success' : ($booking->status === 'PENDING' ? 'warning' : 'danger') }} fs-6">
                            {{ $booking->status === 'CONFIRMED' ? 'Đã xác nhận' : ($booking->status === 'PENDING' ? 'Chờ thanh toán' : 'Đã hủy') }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Mã booking:</small>
                        <p class="mb-0"><strong>{{ $booking->booking_code }}</strong></p>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        @if($booking->status === 'CONFIRMED')
                        <a href="{{ route('tickets.booking.print', $booking) }}" class="btn btn-success btn-sm" target="_blank">
                            <i class="fas fa-print me-1"></i>In vé
                        </a>
                        @endif
                        <a href="{{ route('tickets.show', $booking) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Xem vé
                        </a>
                        @if($booking->status === 'PENDING')
                        <form action="{{ route('my-tickets.cancel', $booking) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn hủy booking này?');">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-times me-1"></i>Hủy
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Bạn chưa có vé nào</h5>
            <p class="text-muted">Hãy đặt vé để xem phim ngay!</p>
            <a href="{{ route('movies.index') }}" class="btn btn-primary">
                <i class="fas fa-film me-2"></i>Xem phim
            </a>
        </div>
    </div>
    @endforelse

    <!-- Pagination -->
    @if($bookings->hasPages())
    <div class="d-flex justify-content-center">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection

