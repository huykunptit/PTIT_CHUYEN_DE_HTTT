@extends('layouts.app')

@section('title', 'Vé của tôi - Cinema')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Thanh toán thành công!</strong> Vui lòng lưu mã vé để check-in khi đến rạp.
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-dark border-secondary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-ticket-alt me-2"></i>Thông tin đặt vé
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong class="text-primary">Mã booking:</strong> <span class="text-white">{{ $booking->booking_code }}</span></p>
                            <p><strong class="text-primary">Phim:</strong> <span class="text-white">{{ $booking->showtime->movie->title }}</span></p>
                            <p><strong class="text-primary">Rạp:</strong> <span class="text-white">{{ $booking->showtime->room->cinema->name }}</span></p>
                            <p><strong class="text-primary">Phòng:</strong> <span class="text-white">{{ $booking->showtime->room->name }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong class="text-primary">Ngày chiếu:</strong> <span class="text-white">{{ $booking->showtime->date->format('d/m/Y') }}</span></p>
                            <p><strong class="text-primary">Giờ chiếu:</strong> <span class="text-white">
                                {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($booking->showtime->end_time)->format('H:i') }}
                            </span></p>
                            <p><strong class="text-primary">Tổng tiền:</strong> <span class="text-success fs-5">{{ number_format($booking->final_amount, 0, ',', '.') }}₫</span></p>
                            <p><strong class="text-primary">Trạng thái:</strong> 
                                <span class="badge bg-success">{{ $booking->status }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h4 class="mb-4">Danh sách vé</h4>
        </div>
        
        @foreach($tickets as $ticket)
        <div class="col-md-6 mb-4">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="text-primary mb-1">Mã vé: {{ $ticket->ticket_code }}</h5>
                            <p class="text-muted mb-0">Ghế: <strong class="text-white">{{ $ticket->seat->row }}{{ $ticket->seat->number }}</strong></p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $ticket->status === 'SOLD' ? 'success' : 'warning' }}">
                                {{ $ticket->status === 'SOLD' ? 'Đã phát vé' : $ticket->status }}
                            </span>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Giá vé</small>
                            <p class="mb-0 text-white">{{ number_format($ticket->price, 0, ',', '.') }}₫</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Loại ghế</small>
                            <p class="mb-0 text-white">
                                @if($ticket->seat->type === 'VIP')
                                    <span class="badge bg-warning text-dark">VIP</span>
                                @elseif($ticket->seat->type === 'COUPLE')
                                    <span class="badge bg-danger">Đôi</span>
                                @else
                                    <span class="badge bg-secondary">Thường</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="alert alert-info mb-3">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Lưu ý:</strong> Vui lòng lưu mã vé <strong>{{ $ticket->ticket_code }}</strong> để check-in tại rạp.
                            Bạn có thể check-in 30 phút trước giờ chiếu.
                        </small>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('tickets.detail', $ticket->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                        </a>
                        <a href="{{ route('tickets.print', $ticket->id) }}" class="btn btn-outline-success btn-sm" target="_blank">
                            <i class="fas fa-print me-1"></i>In vé PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('tickets.booking.print', $booking->id) }}" class="btn btn-success me-2" target="_blank">
                <i class="fas fa-print me-2"></i>In tất cả vé PDF
            </a>
            <a href="{{ route('checkin.show') }}" class="btn btn-primary me-2">
                <i class="fas fa-qrcode me-2"></i>Check-in vé
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="fas fa-home me-2"></i>Về trang chủ
            </a>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
    }
</style>
@endsection

