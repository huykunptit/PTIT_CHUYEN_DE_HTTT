@extends('layouts.admin')

@section('title', 'Báo cáo Doanh thu - Admin Panel')
@section('page-title', 'Báo cáo Doanh thu')

@section('content')
<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.revenue') }}" class="row g-3">
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
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Tổng doanh thu</h6>
                <h3>{{ number_format($totalRevenue, 0, ',', '.') }}₫</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Tổng đặt vé</h6>
                <h3>{{ $totalBookings }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Daily Revenue Chart -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Doanh thu theo ngày</h6>
    </div>
    <div class="card-body">
        <canvas id="dailyRevenueChart" height="80"></canvas>
    </div>
</div>

<!-- Revenue by Cinema -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Doanh thu theo rạp</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Rạp</th>
                        <th>Đặt vé</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenueByCinema as $index => $cinema)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $cinema->name }}</strong></td>
                        <td><span class="badge bg-info">{{ $cinema->booking_count }}</span></td>
                        <td class="text-success"><strong>{{ number_format($cinema->revenue ?? 0, 0, ',', '.') }}₫</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Revenue by Movie -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Doanh thu theo phim</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Phim</th>
                        <th>Đặt vé</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenueByMovie as $index => $movie)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $movie->title }}</strong></td>
                        <td><span class="badge bg-primary">{{ $movie->booking_count }}</span></td>
                        <td class="text-success"><strong>{{ number_format($movie->revenue ?? 0, 0, ',', '.') }}₫</strong></td>
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
const ctx = document.getElementById('dailyRevenueChart').getContext('2d');
const dailyRevenueChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($dailyRevenue->pluck('date')),
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: @json($dailyRevenue->pluck('revenue')),
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
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
</script>
@endsection

