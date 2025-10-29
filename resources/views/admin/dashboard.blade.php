@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Tổng doanh thu</h6>
                        <h3 class="text-white">₫0</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Vé đã bán</h6>
                        <h3 class="text-white">0</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-ticket-alt fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Số phim</h6>
                        <h3 class="text-white">0</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-film fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Số rạp</h6>
                        <h3 class="text-white">0</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-building fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.movies.create') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-plus me-2"></i>Thêm phim mới
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.cinemas.create') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-plus me-2"></i>Thêm rạp mới
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.showtimes.create') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-plus me-2"></i>Tạo lịch chiếu
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-list me-2"></i>Xem đặt vé
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-ticket-alt me-2"></i>Đặt vé gần đây
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã đặt vé</th>
                                <th>Khách hàng</th>
                                <th>Phim</th>
                                <th>Số vé</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                    Chưa có đặt vé nào
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Thống kê
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <i class="fas fa-chart-pie text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">Chưa có dữ liệu thống kê</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
