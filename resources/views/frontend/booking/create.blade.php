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
                                                          @elseif(isset($heldSeats[$seat->id])) seat-held
                                                          @elseif($seat->type == 'VIP') seat-vip 
                                                          @elseif($seat->type == 'COUPLE') seat-couple 
                                                          @else seat-standard @endif"
                                                   data-seat-id="{{ $seat->id }}">
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
                                    <div class="d-flex align-items-center">
                                        <div class="seat-label seat-held me-2"></div>
                                        <span>Đang giữ chỗ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @php 
                            $user = auth()->user(); 
                            $profileEditUrl = Route::has('profile.edit') ? route('profile.edit') : '#';
                        @endphp
                        <div class="card bg-light border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-1">Thông tin liên hệ</h5>
                                        <p class="text-muted mb-0">Thông tin từ tài khoản của bạn</p>
                                    </div>
                                    <a href="{{ $profileEditUrl }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-user-edit me-2"></i>Cập nhật
                                    </a>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="info-chip">
                                            <small class="text-muted d-block mb-1">Họ và tên</small>
                                            <strong>{{ $user->name }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-chip">
                                            <small class="text-muted d-block mb-1">Email</small>
                                            <strong>{{ $user->email }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-chip">
                                            <small class="text-muted d-block mb-1">Số điện thoại</small>
                                            <strong>{{ $user->phone ?? 'Chưa cập nhật' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                @if(!$user->phone)
                                    <div class="alert alert-warning d-flex align-items-center mt-3 mb-0" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <span>Vui lòng cập nhật số điện thoại để nhận thông tin vé dễ dàng hơn.</span>
                                    </div>
                                @endif
                            </div>
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

.seat-held {
    background-color: #ff9800;
    color: white;
    cursor: not-allowed;
    position: relative;
}

.seat-held:hover {
    background-color: #ff9800;
}

.seat-held::after {
    content: '⏱';
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 10px;
}

.info-chip {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 12px 16px;
}

.seat-summary-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.seat-summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
}

.seat-summary-type {
    font-size: 0.85rem;
    color: #64748b;
    text-transform: capitalize;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const showtimeId = {{ $showtime->id }};
    const checkboxes = document.querySelectorAll('.seat-checkbox');
    const bookButton = document.getElementById('bookButton');
    const summary = document.getElementById('bookingSummary');
    const heldSeats = @json($heldSeats ?? []);
    const currentUserId = {{ auth()->id() ?? 'null' }};
    
    // Track held seats
    let seatHoldTimeouts = {};
    
    // Initialize Pusher for realtime updates
    @if(config('broadcasting.default') === 'pusher' && config('broadcasting.connections.pusher.key'))
    try {
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster', 'mt1') }}',
            encrypted: true
        });

        const channel = pusher.subscribe('showtime.' + showtimeId + '.seats');
        
        // Listen for seat held event
        channel.bind('seat.held', function(data) {
            updateSeatStatus(data.seat_id, 'held', data.user_id);
        });
        
        // Listen for seat released event
        channel.bind('seat.released', function(data) {
            updateSeatStatus(data.seat_id, 'released', data.user_id);
        });
        
        // Listen for seat expired event
        channel.bind('seat.expired', function(data) {
            updateSeatStatus(data.seat_id, 'expired', data.user_id);
        });
    } catch (error) {
        console.error('Pusher initialization error:', error);
    }
    @endif
    
    function updateSeatStatus(seatId, action, userId) {
        const checkbox = document.getElementById('seat_' + seatId);
        if (!checkbox) return;
        
        const label = checkbox.nextElementSibling;
        const seatItem = label.closest('.seat-item');
        
        if (action === 'held') {
            // Only mark as held if not held by current user
            if (userId !== currentUserId) {
                label.classList.remove('seat-standard', 'seat-vip', 'seat-couple');
                label.classList.add('seat-held');
                checkbox.disabled = true;
                label.style.pointerEvents = 'none';
            }
        } else if (action === 'released' || action === 'expired') {
            // Only release if not selected by current user
            if (!checkbox.checked) {
                label.classList.remove('seat-held');
                const seatType = checkbox.dataset.type?.toLowerCase() ?? 'standard';
                if (seatType === 'vip') {
                    label.classList.add('seat-vip');
                } else if (seatType === 'couple') {
                    label.classList.add('seat-couple');
                } else {
                    label.classList.add('seat-standard');
                }
                checkbox.disabled = false;
                label.style.pointerEvents = 'auto';
            }
        }
    }
    
    // Hold/release seats when user selects/deselects
    async function holdSeat(seatId) {
        try {
            const response = await fetch('/api/seats/hold', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    showtime_id: showtimeId,
                    seat_id: seatId
                })
            });
            
            const data = await response.json();
            if (!data.success && response.status === 409) {
                // Seat already held by someone else
                const checkbox = document.getElementById('seat_' + seatId);
                if (checkbox) {
                    checkbox.checked = false;
                    updateSummary();
                }
                alert('Ghế này đã được người khác giữ chỗ. Vui lòng chọn ghế khác.');
            }
        } catch (error) {
            console.error('Error holding seat:', error);
        }
    }
    
    async function releaseSeat(seatId) {
        try {
            await fetch('/api/seats/release', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    showtime_id: showtimeId,
                    seat_id: seatId
                })
            });
        } catch (error) {
            console.error('Error releasing seat:', error);
        }
    }
    
    function updateSummary() {
        const selectedSeats = Array.from(checkboxes).filter(cb => cb.checked && !cb.disabled);
        const totalPrice = selectedSeats.reduce((sum, cb) => sum + parseFloat(cb.dataset.price), 0);
        
        if (selectedSeats.length === 0) {
            summary.innerHTML = '<p class="text-muted text-center">Chọn ghế để xem tóm tắt</p>';
            bookButton.disabled = true;
        } else {
            const seatCards = selectedSeats.map(cb => {
                const seatLabel = cb.nextElementSibling.textContent.trim();
                const price = parseFloat(cb.dataset.price);
                const seatType = cb.dataset.type?.toLowerCase() ?? 'standard';

                const typeLabels = {
                    'vip': 'Ghế VIP',
                    'couple': 'Ghế đôi',
                    'standard': 'Ghế thường'
                };

                return `
                    <div class="seat-summary-card mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold fs-5">Ghế ${seatLabel}</div>
                                <div class="seat-summary-type">${typeLabels[seatType] || 'Ghế thường'}</div>
                            </div>
                            <div class="text-primary fw-bold fs-5">${price.toLocaleString('vi-VN')}₫</div>
                        </div>
                    </div>
                `;
            }).join('');

            summary.innerHTML = `
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 text-muted">Ghế đã chọn</h6>
                        <span class="badge bg-primary">${selectedSeats.length} ghế</span>
                    </div>
                    ${seatCards}
                </div>
                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Tổng cộng</span>
                        <h4 class="text-primary mb-0">${totalPrice.toLocaleString('vi-VN')}₫</h4>
                    </div>
                </div>
            `;
            bookButton.disabled = false;
        }
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', async function() {
            const seatId = parseInt(this.value);
            
            if (this.checked) {
                // Hold the seat
                await holdSeat(seatId);
                updateSummary();
            } else {
                // Release the seat
                await releaseSeat(seatId);
                updateSummary();
            }
        });
    });
    
    // Disable occupied and held seats
    document.querySelectorAll('.seat-occupied, .seat-held').forEach(seat => {
        const checkbox = seat.previousElementSibling;
        if (checkbox && checkbox.classList.contains('seat-checkbox')) {
            checkbox.disabled = true;
        }
        seat.style.pointerEvents = 'none';
    });
    
    // Mark initially held seats
    Object.keys(heldSeats).forEach(seatId => {
        const holdInfo = heldSeats[seatId];
        if (holdInfo.user_id !== currentUserId) {
            updateSeatStatus(parseInt(seatId), 'held', holdInfo.user_id);
        }
    });
});
</script>
@endsection
