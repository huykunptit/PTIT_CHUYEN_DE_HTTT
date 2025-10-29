@extends('layouts.app')

@section('title', 'Đặt vé - ' . $showtime->movie->title . ' - Cinema')

@section('content')
<div class="container py-5">
    <!-- Movie Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="bg-dark d-flex align-items-center justify-content-center" 
                                 style="height: 120px; border-radius: 8px;">
                                <i class="fas fa-film text-light" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h4 class="mb-2">{{ $showtime->movie->title }}</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Rạp:</strong> {{ $showtime->room->cinema->name }}</p>
                                    <p class="mb-1"><strong>Phòng:</strong> {{ $showtime->room->name }}</p>
                                    <p class="mb-1"><strong>Ngày:</strong> {{ $showtime->date->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Giờ chiếu:</strong> 
                                        {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}
                                    </p>
                                    <p class="mb-1"><strong>Thời lượng:</strong> {{ $showtime->movie->duration }} phút</p>
                                    <p class="mb-1"><strong>Giới hạn độ tuổi:</strong> 
                                        @if($showtime->movie->age_rating == 0)
                                            P - Mọi lứa tuổi
                                        @else
                                            {{ $showtime->movie->age_rating }}+
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Seat Selection -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chair me-2"></i>Chọn ghế
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
                        @csrf
                        <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                        
                        <!-- Screen -->
                        <div class="text-center mb-4">
                            <div class="bg-dark text-light py-2 px-4 d-inline-block rounded">
                                <i class="fas fa-tv me-2"></i>Màn hình
                            </div>
                        </div>
                        
                        <!-- Seat Layout -->
                        <div class="seat-layout mb-4">
                            @php
                                $seatsByRow = $seats->groupBy('row');
                            @endphp
                            
                            @foreach($seatsByRow as $row => $rowSeats)
                            <div class="row mb-2">
                                <div class="col-1 text-center">
                                    <strong>{{ $row }}</strong>
                                </div>
                                <div class="col-11">
                                    <div class="d-flex gap-2">
                                        @foreach($rowSeats as $seat)
                                        <div class="seat-item">
                                            <input type="checkbox" 
                                                   class="seat-checkbox d-none" 
                                                   id="seat_{{ $seat->id }}" 
                                                   name="seat_ids[]" 
                                                   value="{{ $seat->id }}"
                                                   data-price="{{ $seat->price }}"
                                                   data-type="{{ $seat->type }}">
                                            <label for="seat_{{ $seat->id }}" 
                                                   class="seat-label 
                                                          @if($seat->tickets->count() > 0) seat-occupied 
                                                          @elseif($seat->type == 'VIP') seat-vip 
                                                          @elseif($seat->type == 'COUPLE') seat-couple 
                                                          @else seat-standard @endif">
                                                {{ $seat->number }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Legend -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6>Chú thích:</h6>
                                <div class="d-flex gap-3">
                                    <div class="d-flex align-items-center">
                                        <div class="seat-label seat-standard me-2"></div>
                                        <span>Ghế thường</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="seat-label seat-vip me-2"></div>
                                        <span>Ghế VIP</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="seat-label seat-couple me-2"></div>
                                        <span>Ghế đôi</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="seat-label seat-occupied me-2"></div>
                                        <span>Đã bán</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Customer Info -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                   id="customer_email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-lg" id="bookButton" disabled>
                                <i class="fas fa-ticket-alt me-2"></i>Đặt vé
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Tóm tắt đặt vé</h6>
                </div>
                <div class="card-body">
                    <div id="bookingSummary">
                        <p class="text-muted">Chọn ghế để xem tóm tắt</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.seat-label {
    display: inline-block;
    width: 40px;
    height: 40px;
    line-height: 40px;
    text-align: center;
    border: 2px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: bold;
}

.seat-checkbox:checked + .seat-label {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.seat-standard {
    background-color: #f8f9fa;
    color: #495057;
}

.seat-vip {
    background-color: #ffc107;
    color: #000;
}

.seat-couple {
    background-color: #e83e8c;
    color: white;
}

.seat-occupied {
    background-color: #dc3545;
    color: white;
    cursor: not-allowed;
}

.seat-occupied:hover {
    background-color: #dc3545;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.seat-checkbox');
    const bookButton = document.getElementById('bookButton');
    const summary = document.getElementById('bookingSummary');
    
    function updateSummary() {
        const selectedSeats = Array.from(checkboxes).filter(cb => cb.checked);
        const totalPrice = selectedSeats.reduce((sum, cb) => sum + parseFloat(cb.dataset.price), 0);
        
        if (selectedSeats.length === 0) {
            summary.innerHTML = '<p class="text-muted">Chọn ghế để xem tóm tắt</p>';
            bookButton.disabled = true;
        } else {
            summary.innerHTML = `
                <div class="mb-3">
                    <h6>Số ghế đã chọn: ${selectedSeats.length}</h6>
                    <ul class="list-unstyled">
                        ${selectedSeats.map(cb => {
                            const seatLabel = cb.nextElementSibling;
                            return `<li>Ghế ${seatLabel.textContent} - ${parseFloat(cb.dataset.price).toLocaleString('vi-VN')}₫</li>`;
                        }).join('')}
                    </ul>
                </div>
                <div class="border-top pt-3">
                    <h5 class="text-primary">Tổng cộng: ${totalPrice.toLocaleString('vi-VN')}₫</h5>
                </div>
            `;
            bookButton.disabled = false;
        }
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSummary);
    });
    
    // Disable occupied seats
    document.querySelectorAll('.seat-occupied').forEach(seat => {
        seat.style.pointerEvents = 'none';
    });
});
</script>
@endsection
