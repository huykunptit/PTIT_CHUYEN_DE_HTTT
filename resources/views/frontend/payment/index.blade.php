@extends('layouts.app')

@section('title', 'Thanh toán - Cinema')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Booking Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-ticket-alt me-2"></i>Tóm tắt đặt vé
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="bg-dark d-flex align-items-center justify-content-center" 
                                 style="height: 120px; border-radius: 8px;">
                                <i class="fas fa-film text-light" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-2">{{ $booking->showtime->movie->title }}</h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>Rạp:</strong> {{ $booking->showtime->room->cinema->name }}</p>
                                    <p class="mb-1"><strong>Phòng:</strong> {{ $booking->showtime->room->name }}</p>
                                    <p class="mb-1"><strong>Ngày:</strong> {{ $booking->showtime->date->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>Giờ chiếu:</strong> 
                                        {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($booking->showtime->end_time)->format('H:i') }}
                                    </p>
                                    <p class="mb-1"><strong>Mã đặt vé:</strong> {{ $booking->booking_code }}</p>
                                    <p class="mb-1"><strong>Trạng thái:</strong> 
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
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Selected Seats -->
                    <div class="mt-4">
                        <h6>Ghế đã chọn:</h6>
                        <div class="row">
                            @foreach($booking->tickets as $ticket)
                            <div class="col-md-3 mb-2">
                                <div class="border rounded p-2 text-center">
                                    <strong>{{ $ticket->seat->row }}{{ $ticket->seat->number }}</strong>
                                    <br>
                                    <small class="text-muted">{{ number_format($ticket->price, 0, ',', '.') }}₫</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Methods -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Phương thức thanh toán
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('payment.vnpay', $booking) }}" id="paymentForm">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="vnpay_qr" value="vnpay_qr" checked>
                                    <label class="form-check-label" for="vnpay_qr">
                                        <i class="fas fa-qrcode text-primary me-2"></i>
                                        <strong>VNPAY QR</strong>
                                        <br>
                                        <small class="text-muted">Quét mã QR để thanh toán</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="vnpay_atm" value="vnpay_atm">
                                    <label class="form-check-label" for="vnpay_atm">
                                        <i class="fas fa-credit-card text-success me-2"></i>
                                        <strong>Thẻ ATM</strong>
                                        <br>
                                        <small class="text-muted">Thanh toán qua thẻ ATM</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="vnpay_card" value="vnpay_card">
                                    <label class="form-check-label" for="vnpay_card">
                                        <i class="fas fa-credit-card text-warning me-2"></i>
                                        <strong>Thẻ quốc tế</strong>
                                        <br>
                                        <small class="text-muted">Visa, MasterCard</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Total Amount -->
                        <div class="border-top pt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Tổng tiền vé:</h6>
                                    <h6>Phí dịch vụ:</h6>
                                    <h6>Giảm giá:</h6>
                                    <hr>
                                    <h5 class="text-primary">Tổng cộng:</h5>
                                </div>
                                <div class="col-md-6 text-end">
                                    <h6>{{ number_format($booking->total_amount, 0, ',', '.') }}₫</h6>
                                    <h6>0₫</h6>
                                    <h6>-{{ number_format($booking->discount_amount, 0, ',', '.') }}₫</h6>
                                    <hr>
                                    <h5 class="text-primary">{{ number_format($booking->final_amount, 0, ',', '.') }}₫</h5>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Countdown Timer -->
                        @if($booking->status == 'PENDING' && $booking->expires_at)
                        <div class="alert alert-warning mt-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock me-2"></i>
                                <span>Thời gian giữ vé còn lại: </span>
                                <span id="countdown" class="fw-bold ms-2"></span>
                            </div>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('home') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="payButton">
                                <i class="fas fa-credit-card me-2"></i>Thanh toán ngay
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($booking->status == 'PENDING' && $booking->expires_at)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const expiresAt = new Date('{{ $booking->expires_at }}').getTime();
    const countdownElement = document.getElementById('countdown');
    
    function updateCountdown() {
        const now = new Date().getTime();
        const distance = expiresAt - now;
        
        if (distance < 0) {
            countdownElement.innerHTML = 'Hết hạn';
            document.getElementById('payButton').disabled = true;
            return;
        }
        
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        countdownElement.innerHTML = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
});
</script>
@endif
@endsection
