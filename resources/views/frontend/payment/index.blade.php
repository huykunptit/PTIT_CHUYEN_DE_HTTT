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
                    
                    <!-- Promotion Code -->
                    <div class="mt-4 border-top pt-4">
                        <h6 class="mb-3">
                            <i class="fas fa-tag me-2 text-primary"></i>Mã giảm giá
                        </h6>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="promotionCode" 
                                           placeholder="Nhập mã giảm giá">
                                    <button class="btn btn-primary" type="button" id="applyPromotionBtn">
                                        <i class="fas fa-check me-2"></i>Áp dụng
                                    </button>
                                </div>
                                <div id="promotionMessage" class="mt-2"></div>
                            </div>
                        </div>
                        <div id="promotionInfo" class="mt-3" style="display: none;">
                            <div class="alert alert-success">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong id="promotionName"></strong>
                                        <div class="small">Giảm: <span id="discountAmount"></span></div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="removePromotionBtn">
                                        <i class="fas fa-times"></i> Xóa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Price Summary -->
                    <div class="mt-4 border-top pt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng tiền:</span>
                            <strong id="totalAmount">{{ number_format($booking->total_amount, 0, ',', '.') }}₫</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2" id="discountRow" style="display: none;">
                            <span class="text-success">Giảm giá:</span>
                            <strong class="text-success" id="discountAmountDisplay">-0₫</strong>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2">
                            <span class="fs-5"><strong>Thành tiền:</strong></span>
                            <span class="fs-5 text-primary"><strong id="finalAmount">{{ number_format($booking->final_amount, 0, ',', '.') }}₫</strong></span>
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
                    <ul class="nav nav-tabs mb-4" id="paymentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="vnpay-tab" data-bs-toggle="tab" data-bs-target="#vnpay-panel" type="button" role="tab">
                                <i class="fas fa-qrcode me-2"></i>VNPay
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sepay-tab" data-bs-toggle="tab" data-bs-target="#sepay-panel" type="button" role="tab">
                                <i class="fas fa-university me-2"></i>Chuyển khoản ngân hàng
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="paymentTabsContent">
                        <!-- VNPay Tab -->
                        <div class="tab-pane fade show active" id="vnpay-panel" role="tabpanel">
                            <form method="POST" action="{{ route('payment.vnpay', $booking) }}" id="vnpayForm">
                                @csrf
                                <input type="hidden" name="promotion_id" id="promotionIdInput" value="">
                                
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
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-credit-card me-2"></i>Thanh toán VNPay
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- SePay Tab -->
                        <div class="tab-pane fade" id="sepay-panel" role="tabpanel">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Hướng dẫn thanh toán</h6>
                                <p class="mb-2">Vui lòng chuyển khoản đúng số tiền với nội dung chuyển khoản:</p>
                                <div class="bg-light p-3 rounded mb-3">
                                    <code class="text-primary fs-5">SE{{ $booking->booking_code }}</code>
                                </div>
                                <p class="mb-0"><small>Hệ thống sẽ tự động xác nhận thanh toán sau khi nhận được chuyển khoản.</small></p>
                            </div>

                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-university me-2"></i>Thông tin tài khoản ngân hàng</h6>
                                    <p class="mb-1"><strong>Số tiền:</strong> <span class="text-primary fs-5">{{ number_format($booking->final_amount, 0, ',', '.') }}₫</span></p>
                                    <p class="mb-1"><strong>Nội dung chuyển khoản:</strong> <code>SE{{ $booking->booking_code }}</code></p>
                                    <p class="mb-0"><small class="text-muted">Vui lòng chuyển khoản đúng số tiền và nội dung để hệ thống tự động xác nhận.</small></p>
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Lưu ý:</strong> Sau khi chuyển khoản, hệ thống sẽ tự động cập nhật trạng thái đặt vé trong vòng vài phút. 
                                Vui lòng không chuyển khoản lại nếu đã chuyển khoản thành công.
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
                    </div>
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
        
        // Warning when less than 2 minutes
        if (distance < 120000) { // 2 minutes
            countdownElement.parentElement.classList.remove('alert-warning');
            countdownElement.parentElement.classList.add('alert-danger');
        } else if (distance < 300000) { // 5 minutes
            countdownElement.parentElement.classList.remove('alert-info');
            countdownElement.parentElement.classList.add('alert-warning');
        }
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
});
</script>
@endif

<script>
// Promotion code handling
document.addEventListener('DOMContentLoaded', function() {
    const bookingId = {{ $booking->id }};
    const applyBtn = document.getElementById('applyPromotionBtn');
    const removeBtn = document.getElementById('removePromotionBtn');
    const codeInput = document.getElementById('promotionCode');
    const messageDiv = document.getElementById('promotionMessage');
    const promotionInfo = document.getElementById('promotionInfo');
    const discountRow = document.getElementById('discountRow');
    
    let appliedPromotion = null;
    const promotionIdInput = document.getElementById('promotionIdInput');
    
    applyBtn.addEventListener('click', async function() {
        const code = codeInput.value.trim().toUpperCase();
        
        if (!code) {
            messageDiv.innerHTML = '<div class="text-danger">Vui lòng nhập mã giảm giá</div>';
            return;
        }
        
        applyBtn.disabled = true;
        applyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang kiểm tra...';
        
        try {
            const response = await fetch('/api/promotions/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    booking_id: bookingId
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                appliedPromotion = data.data;
                messageDiv.innerHTML = '<div class="text-success">' + data.message + '</div>';
                promotionInfo.style.display = 'block';
                document.getElementById('promotionName').textContent = appliedPromotion.promotion.name;
                document.getElementById('discountAmount').textContent = '-' + appliedPromotion.discount_amount.toLocaleString('vi-VN') + '₫';
                discountRow.style.display = 'flex';
                document.getElementById('discountAmountDisplay').textContent = '-' + appliedPromotion.discount_amount.toLocaleString('vi-VN') + '₫';
                document.getElementById('finalAmount').textContent = appliedPromotion.final_amount.toLocaleString('vi-VN') + '₫';
                promotionIdInput.value = appliedPromotion.promotion.id;
                codeInput.disabled = true;
            } else {
                messageDiv.innerHTML = '<div class="text-danger">' + data.message + '</div>';
            }
        } catch (error) {
            messageDiv.innerHTML = '<div class="text-danger">Có lỗi xảy ra. Vui lòng thử lại.</div>';
        } finally {
            applyBtn.disabled = false;
            applyBtn.innerHTML = '<i class="fas fa-check me-2"></i>Áp dụng';
        }
    });
    
    removeBtn.addEventListener('click', function() {
        appliedPromotion = null;
        promotionInfo.style.display = 'none';
        discountRow.style.display = 'none';
        messageDiv.innerHTML = '';
        codeInput.value = '';
        codeInput.disabled = false;
        promotionIdInput.value = '';
        document.getElementById('finalAmount').textContent = '{{ number_format($booking->total_amount, 0, ',', '.') }}₫';
    });
    
    // Allow Enter key to apply
    codeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyBtn.click();
        }
    });
});
</script>
@endsection
