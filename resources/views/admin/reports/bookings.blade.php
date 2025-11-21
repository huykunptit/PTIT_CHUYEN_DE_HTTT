@extends('layouts.admin')

@section('title', 'Báo cáo Đặt vé - Admin Panel')
@section('page-title', 'Báo cáo Đặt vé')

@section('content')
<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.bookings') }}" class="row g-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Từ ngày</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">Đến ngày</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter me-2"></i>Lọc
                </button>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Tổng đặt vé</h6>
                <h3>{{ $totalBookings }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Đã xác nhận</h6>
                    <h3>{{ $bookingsByStatus->where('status', 'CONFIRMED')->first()->count ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Chờ thanh toán</h6>
                    <h3>{{ $bookingsByStatus->where('status', 'PENDING')->first()->count ?? 0 }}</h3>
                </div>
            </div>
    </div>
</div>

<!-- Bookings by Status -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Đặt vé theo trạng thái</h6>
    </div>
    <div class="card-body">
        <canvas id="bookingStatusChart" height="80"></canvas>
    </div>
</div>

<!-- Bookings by Date -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Đặt vé theo ngày</h6>
    </div>
    <div class="card-body">
        <canvas id="bookingsByDateChart" height="80"></canvas>
    </div>
</div>

<!-- Top Booked Movies -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Top phim được đặt nhiều nhất</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Phim</th>
                        <th>Thể loại</th>
                        <th>Số đặt vé</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topBookedMovies as $index => $movie)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $movie->title }}</strong></td>
                        <td>{{ $movie->genre }}</td>
                        <td><span class="badge bg-primary">{{ $movie->booking_count }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Booking Status Chart
const statusCtx = document.getElementById('bookingStatusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: @json(array_keys($bookingsByStatus->pluck('count', 'status')->toArray())),
        datasets: [{
            data: @json(array_values($bookingsByStatus->pluck('count', 'status')->toArray())),
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
        maintainAspectRatio: false
    }
});

// Bookings by Date Chart
const dateCtx = document.getElementById('bookingsByDateChart').getContext('2d');
const dateChart = new Chart(dateCtx, {
    type: 'line',
    data: {
        labels: @json($bookingsByDate->pluck('date')),
        datasets: [
            {
                label: 'Tổng',
                data: @json($bookingsByDate->pluck('count')),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4
            },
            {
                label: 'Đã xác nhận',
                data: @json($bookingsByDate->pluck('confirmed')),
                borderColor: 'rgb(40, 167, 69)',
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                tension: 0.4
            },
            {
                label: 'Chờ thanh toán',
                data: @json($bookingsByDate->pluck('pending')),
                borderColor: 'rgb(255, 193, 7)',
                backgroundColor: 'rgba(255, 193, 7, 0.2)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection

