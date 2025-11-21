@extends('layouts.app')

@section('title', 'Thanh toán - Cinema')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Payment Method Header -->
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        @if($method === 'vnpay_qr')
                            <i class="fas fa-qrcode me-2"></i>Thanh toán bằng QR Code
                        @elseif($method === 'vnpay_atm')
                            <i class="fas fa-credit-card me-2"></i>Thanh toán bằng Thẻ ATM
                        @else
                            <i class="fas fa-globe me-2"></i>Thanh toán bằng Thẻ quốc tế
                        @endif
                    </h4>
                </div>
            </div>

            <!-- Booking Info -->
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-primary">Mã đặt vé:</h6>
                            <p class="text-white">{{ $booking->booking_code }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Tổng tiền:</h6>
                            <p class="text-white fs-4 text-success">{{ number_format($booking->final_amount, 0, ',', '.') }}₫</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-primary">Phim:</h6>
                            <p class="text-white">{{ $booking->showtime->movie->title }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($method === 'vnpay_qr')
            <!-- QR Code Payment -->
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-body text-center">
                    <h5 class="text-white mb-4">Quét mã QR để thanh toán</h5>
                    
                    <!-- QR Code Placeholder -->
                    <div class="bg-white p-4 d-inline-block rounded mb-4" style="border: 2px solid #ddd;">
                        <div style="width: 250px; height: 250px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="fas fa-qrcode text-dark mb-2" style="font-size: 4rem;"></i>
                            <small class="text-muted">Mã QR Demo</small>
                            <small class="text-muted">Booking: {{ $booking->booking_code }}</small>
                            <small class="text-muted">{{ number_format($booking->final_amount, 0, ',', '.') }}₫</small>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Hướng dẫn:</strong>
                        <ol class="text-start mt-2 mb-0">
                            <li>Mở ứng dụng ngân hàng trên điện thoại</li>
                            <li>Chọn tính năng "Quét mã QR"</li>
                            <li>Quét mã QR trên màn hình</li>
                            <li>Xác nhận thanh toán</li>
                        </ol>
                    </div>

                    <div class="mt-4">
                        <form method="POST" action="{{ route('payment.demo.process', $booking->id) }}">
                            @csrf
                            <input type="hidden" name="method" value="{{ $method }}">
                            <input type="hidden" name="action" value="success">
                            <button type="submit" class="btn btn-success btn-lg me-2">
                                <i class="fas fa-check-circle me-2"></i>Đã thanh toán thành công (Demo)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @elseif($method === 'vnpay_atm')
            <!-- ATM Card Payment -->
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-body">
                    <h5 class="text-white mb-4">Nhập thông tin thẻ ATM</h5>
                    
                    <form method="POST" action="{{ route('payment.demo.process', $booking->id) }}">
                        @csrf
                        <input type="hidden" name="method" value="{{ $method }}">
                        
                        <div class="mb-3">
                            <label class="form-label text-white">Số thẻ</label>
                            <input type="text" class="form-control" placeholder="1234 5678 9012 3456" 
                                   value="1234 5678 9012 3456" readonly>
                            <small class="text-muted">(Demo: Số thẻ mẫu)</small>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-white">Ngày hết hạn</label>
                                <input type="text" class="form-control" placeholder="MM/YY" value="12/25" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">CVV</label>
                                <input type="text" class="form-control" placeholder="123" value="123" readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-white">Tên chủ thẻ</label>
                            <input type="text" class="form-control" placeholder="NGUYEN VAN A" value="NGUYEN VAN A" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-white">OTP (Mã xác thực)</label>
                            <input type="text" class="form-control" placeholder="Nhập mã OTP" value="123456" readonly>
                            <small class="text-muted">(Demo: Mã OTP mẫu)</small>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Chế độ Demo:</strong> Thông tin thẻ đã được điền sẵn. Click "Thanh toán" để simulate thanh toán thành công.
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('payment.index', $booking) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" name="action" value="success" class="btn btn-success btn-lg flex-grow-1">
                                <i class="fas fa-credit-card me-2"></i>Thanh toán ({{ number_format($booking->final_amount, 0, ',', '.') }}₫)
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @else
            <!-- International Card Payment -->
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-body">
                    <h5 class="text-white mb-4">Nhập thông tin thẻ quốc tế (Visa/MasterCard)</h5>
                    
                    <div class="mb-3 text-center">
                        <i class="fab fa-cc-visa text-primary me-3" style="font-size: 3rem;"></i>
                        <i class="fab fa-cc-mastercard text-warning" style="font-size: 3rem;"></i>
                    </div>
                    
                    <form method="POST" action="{{ route('payment.demo.process', $booking->id) }}">
                        @csrf
                        <input type="hidden" name="method" value="{{ $method }}">
                        
                        <div class="mb-3">
                            <label class="form-label text-white">Card Number</label>
                            <input type="text" class="form-control" placeholder="4111 1111 1111 1111" 
                                   value="4111 1111 1111 1111" readonly>
                            <small class="text-muted">(Demo: Visa test card)</small>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-white">Expiry Date</label>
                                <input type="text" class="form-control" placeholder="MM/YY" value="12/25" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">CVV</label>
                                <input type="text" class="form-control" placeholder="123" value="123" readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-white">Cardholder Name</label>
                            <input type="text" class="form-control" placeholder="NGUYEN VAN A" value="NGUYEN VAN A" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-white">Billing Address</label>
                            <input type="text" class="form-control" placeholder="123 Main St, Ho Chi Minh City" 
                                   value="123 Main St, Ho Chi Minh City" readonly>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Chế độ Demo:</strong> Tất cả thông tin đã được điền sẵn. Click "Pay Now" để simulate thanh toán thành công.
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('payment.index', $booking) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" name="action" value="success" class="btn btn-success btn-lg flex-grow-1">
                                <i class="fas fa-check me-2"></i>Pay Now ({{ number_format($booking->final_amount, 0, ',', '.') }}₫)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Cancel Payment -->
            <div class="text-center mt-4">
                <form method="POST" action="{{ route('payment.demo.process', $booking->id) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="method" value="{{ $method }}">
                    <button type="submit" name="action" value="cancel" class="btn btn-outline-danger">
                        <i class="fas fa-times me-2"></i>Hủy thanh toán
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }
    
    input[readonly] {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
</style>
@endsection

