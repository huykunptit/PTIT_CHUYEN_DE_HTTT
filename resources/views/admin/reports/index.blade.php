@extends('layouts.admin')

@section('title', 'Báo cáo - Admin Panel')
@section('page-title', 'Báo cáo')

@section('content')
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                <h5>Báo cáo Doanh thu</h5>
                <p class="text-muted">Xem báo cáo doanh thu theo ngày, rạp, phim</p>
                <a href="{{ route('admin.reports.revenue') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-2"></i>Xem báo cáo
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-ticket-alt fa-3x text-success mb-3"></i>
                <h5>Báo cáo Đặt vé</h5>
                <p class="text-muted">Xem báo cáo đặt vé theo trạng thái, thời gian</p>
                <a href="{{ route('admin.reports.bookings') }}" class="btn btn-success">
                    <i class="fas fa-arrow-right me-2"></i>Xem báo cáo
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-chart-pie fa-3x text-info mb-3"></i>
                <h5>Báo cáo Tổng hợp</h5>
                <p class="text-muted">Xem báo cáo tổng hợp các chỉ số</p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-info">
                    <i class="fas fa-arrow-right me-2"></i>Xem Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

