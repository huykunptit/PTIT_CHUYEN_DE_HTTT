@extends('layouts.admin')

@section('title', 'Chi tiết đặt vé - Admin Panel')
@section('page-title', 'Chi tiết đặt vé')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Thông tin đặt vé</h5>
                <div>
                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Sửa
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Thông tin khách hàng</h6>
                        @if($booking->user)
                            <p><strong>Tên:</strong> {{ $booking->user->name }}</p>
                            <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $booking->user->phone ?? '-' }}</p>
                            <p><strong>Loại khách:</strong> 
                                @if($booking->user->is_member)
                                    <span class="badge bg-primary">Thành viên</span>
                                @else
                                    <span class="badge bg-secondary">Khách thường</span>
                                @endif
                            </p>
                        @else
                            <p class="text-muted">Khách vãng lai</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6>Thông tin đặt vé</h6>
                        <p><strong>Mã đặt vé:</strong> {{ $booking->booking_code }}</p>
                        <p><strong>Ngày đặt:</strong> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Trạng thái:</strong> 
                            @switch($booking->status)
                                @case('PENDING')
                                    <span class="badge bg-warning">Chờ thanh toán</span>
                                    @break
                                @case('CONFIRMED')
                                    <span class="badge bg-success">Đã xác nhận</span>
                                    @break
                                @case('CANCELLED')
                                    <span class="badge bg-danger">Đã hủy</span>
                                    @break
                                @case('EXPIRED')
                                    <span class="badge bg-secondary">Hết hạn</span>
                                    @break
                            @endswitch
                        </p>
                        @if($booking->status === 'PENDING' && $booking->expires_at)
                            <p><strong>Hết hạn:</strong> {{ $booking->expires_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Thông tin phim</h6>
                        <p><strong>Tên phim:</strong> {{ $booking->showtime->movie->title }}</p>
                        <p><strong>Thể loại:</strong> {{ $booking->showtime->movie->genre }}</p>
                        <p><strong>Thời lượng:</strong> {{ $booking->showtime->movie->duration }} phút</p>
                        <p><strong>Giới hạn độ tuổi:</strong> 
                            @if($booking->showtime->movie->age_rating == 0)
                                P - Mọi lứa tuổi
                            @else
                                {{ $booking->showtime->movie->age_rating }}+
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Thông tin chiếu</h6>
                        <p><strong>Rạp:</strong> {{ $booking->showtime->room->cinema->name }}</p>
                        <p><strong>Phòng:</strong> {{ $booking->showtime->room->name }}</p>
                        <p><strong>Ngày:</strong> {{ $booking->showtime->date->format('d/m/Y') }}</p>
                        <p><strong>Giờ chiếu:</strong> 
                            {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($booking->showtime->end_time)->format('H:i') }}
                        </p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Ghế đã chọn</h6>
                        <div class="row">
                            @foreach($booking->tickets as $ticket)
                            <div class="col-md-4 mb-2">
                                <div class="border rounded p-2 text-center">
                                    <strong>{{ $ticket->seat->row }}{{ $ticket->seat->number }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $ticket->seat->type }}</small>
                                    <br>
                                    <small class="text-primary">{{ number_format($ticket->price, 0, ',', '.') }}₫</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Thanh toán</h6>
                        <p><strong>Tổng tiền vé:</strong> {{ number_format($booking->total_amount, 0, ',', '.') }}₫</p>
                        @if($booking->discount_amount > 0)
                            <p><strong>Giảm giá:</strong> -{{ number_format($booking->discount_amount, 0, ',', '.') }}₫</p>
                        @endif
                        <p><strong>Thành tiền:</strong> 
                            <span class="text-primary fw-bold">{{ number_format($booking->final_amount, 0, ',', '.') }}₫</span>
                        </p>
                        @if($booking->payment_method)
                            <p><strong>Phương thức:</strong> {{ $booking->payment_method }}</p>
                        @endif
                        @if($booking->payment_status)
                            <p><strong>Trạng thái thanh toán:</strong> {{ $booking->payment_status }}</p>
                        @endif
                    </div>
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
                <h6 class="mb-0">In vé PDF</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <i class="fas fa-ticket-alt text-muted" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2 mb-3">In tất cả vé trong đơn hàng này</p>
                    <a href="{{ route('tickets.booking.print', $booking->id) }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="fas fa-print me-1"></i>In tất cả vé PDF
                    </a>
                </div>
                @if($booking->tickets->count() > 0)
                <hr>
                <div class="mt-3">
                    <small class="text-muted d-block mb-2">Hoặc in từng vé:</small>
                    <div class="d-grid gap-2">
                        @foreach($booking->tickets as $ticket)
                        <a href="{{ route('tickets.print', $ticket->id) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="fas fa-print me-1"></i>In vé {{ $ticket->ticket_code }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
