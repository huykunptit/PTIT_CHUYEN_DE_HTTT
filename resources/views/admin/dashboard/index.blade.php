@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')
@section('page-title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Tổng phim</h6>
                        <h3 class="mb-0">{{ $stats['total_movies'] }}</h3>
                        <small>
                            <i class="fas fa-film me-1"></i>
                            {{ $stats['now_showing'] }} đang chiếu
                        </small>
                    </div>
                    <div>
                        <i class="fas fa-film fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Doanh thu</h6>
                        <h3 class="mb-0">{{ number_format($stats['total_revenue'], 0, ',', '.') }}₫</h3>
                        <small>
                            <i class="fas fa-check-circle me-1"></i>
                            {{ $stats['confirmed_bookings'] }} booking
                        </small>
                    </div>
                    <div>
                        <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Tổng đặt vé</h6>
                        <h3 class="mb-0">{{ $stats['total_bookings'] }}</h3>
                        <small>
                            <i class="fas fa-clock me-1"></i>
                            {{ $stats['pending_bookings'] }} chờ thanh toán
                        </small>
                    </div>
                    <div>
                        <i class="fas fa-ticket-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Rạp & Phòng</h6>
                        <h3 class="mb-0">{{ $stats['total_cinemas'] }} / {{ $stats['total_rooms'] }}</h3>
                        <small>
                            <i class="fas fa-building me-1"></i>
                            {{ $stats['total_users'] }} users
                        </small>
                    </div>
                    <div>
                        <i class="fas fa-building fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Revenue Chart -->
    <div class="col-md-8 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Doanh thu 12 tháng gần nhất
                </h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    

</div>

<div class="row mb-4">
    <!-- Top Movies -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-trophy me-2 text-warning"></i>Top 5 phim bán chạy nhất
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Phim</th>
                                <th>Đặt vé</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topMovies as $index => $movie)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $movie->title }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $movie->genre }}</small>
                                </td>
                                <td><span class="badge bg-primary">{{ $movie->booking_count }}</span></td>
                                <td class="text-success"><strong>{{ number_format($movie->revenue ?? 0, 0, ',', '.') }}₫</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Cinemas -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-building me-2 text-primary"></i>Top 5 rạp chiếu doanh thu cao nhất
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Rạp</th>
                                <th>Đặt vé</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCinemas as $index => $cinema)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $cinema->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($cinema->address, 30) }}</small>
                                </td>
                                <td><span class="badge bg-info">{{ $cinema->booking_count }}</span></td>
                                <td class="text-success"><strong>{{ number_format($cinema->revenue ?? 0, 0, ',', '.') }}₫</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Showtimes -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Lịch chiếu hôm nay
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Phim</th>
                                <th>Rạp</th>
                                <th>Phòng</th>
                                <th>Giờ chiếu</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayShowtimes as $showtime)
                            <tr>
                                <td>{{ $showtime->movie->title }}</td>
                                <td>{{ $showtime->room->cinema->name }}</td>
                                <td>{{ $showtime->room->name }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}
                                </td>
                                <td>
                                    @if($showtime->status === 'ACTIVE')
                                        <span class="badge bg-success">Hoạt động</span>
                                    @elseif($showtime->status === 'CANCELLED')
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $showtime->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Không có lịch chiếu nào hôm nay</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($monthlyRevenue->pluck('month')),
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: @json($monthlyRevenue->pluck('revenue')),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                    }
                }
            }
        }
    }
});

// Booking Status Chart
const statusCtx = document.getElementById('bookingStatusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: @json(array_keys($bookingStatus->toArray())),
        datasets: [{
            data: @json(array_values($bookingStatus->toArray())),
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(108, 117, 125, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endsection

