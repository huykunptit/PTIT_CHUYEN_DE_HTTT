@extends('layouts.staff')

@section('title', 'Staff Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
    <span class="badge bg-primary">Nhân viên</span>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Đặt vé hôm nay</div>
            <div class="stat-value">{{ number_format($stats['today_bookings']) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="stat-label">Doanh thu hôm nay</div>
            <div class="stat-value">{{ number_format($stats['today_revenue'], 0, ',', '.') }}₫</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="stat-label">Đặt vé tháng này</div>
            <div class="stat-value">{{ number_format($stats['month_bookings']) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
            <div class="stat-label">Doanh thu tháng này</div>
            <div class="stat-value">{{ number_format($stats['month_revenue'], 0, ',', '.') }}₫</div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <div class="stat-label">Đang chờ xử lý</div>
            <div class="stat-value">{{ number_format($stats['pending_bookings']) }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
            <div class="stat-label">Đã xác nhận</div>
            <div class="stat-value">{{ number_format($stats['confirmed_bookings']) }}</div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Đặt vé gần đây</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã đặt vé</th>
                        <th>Khách hàng</th>
                        <th>Phim</th>
                        <th>Rạp</th>
                        <th>Ngày chiếu</th>
                        <th>Số tiền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                    <tr>
                        <td><strong>{{ $booking->booking_code }}</strong></td>
                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                        <td>{{ $booking->showtime->movie->title }}</td>
                        <td>{{ $booking->showtime->room->cinema->name }}</td>
                        <td>{{ $booking->showtime->date->format('d/m/Y') }} {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}</td>
                        <td>{{ number_format($booking->final_amount, 0, ',', '.') }}₫</td>
                        <td>
                            <span class="badge bg-{{ $booking->status === 'CONFIRMED' ? 'success' : ($booking->status === 'PENDING' ? 'warning' : 'danger') }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('staff.bookings.show', $booking) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Chưa có đặt vé nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Today's Showtimes -->
@if($todayShowtimes->count() > 0)
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Lịch chiếu hôm nay</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Giờ chiếu</th>
                        <th>Phim</th>
                        <th>Rạp</th>
                        <th>Phòng</th>
                        <th>Số lượng đặt vé</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todayShowtimes as $showtime)
                    <tr>
                        <td><strong>{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</strong></td>
                        <td>{{ $showtime->movie_title }}</td>
                        <td>{{ $showtime->cinema_name }}</td>
                        <td>{{ $showtime->room_name }}</td>
                        <td><span class="badge bg-primary">{{ $showtime->booking_count }} vé</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

