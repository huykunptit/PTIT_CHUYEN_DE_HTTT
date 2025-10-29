@extends('layouts.admin')

@section('title', 'Sửa đặt vé - Admin Panel')
@section('page-title', 'Sửa đặt vé')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thông tin đặt vé</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Thông tin khách hàng</h6>
                            @if($booking->user)
                                <p><strong>Tên:</strong> {{ $booking->user->name }}</p>
                                <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                                <p><strong>Số điện thoại:</strong> {{ $booking->user->phone ?? '-' }}</p>
                            @else
                                <p class="text-muted">Khách vãng lai</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Thông tin đặt vé</h6>
                            <p><strong>Mã đặt vé:</strong> {{ $booking->booking_code }}</p>
                            <p><strong>Ngày đặt:</strong> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                            @if($booking->expires_at)
                                <p><strong>Hết hạn:</strong> {{ $booking->expires_at->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Thông tin phim</h6>
                            <p><strong>Tên phim:</strong> {{ $booking->showtime->movie->title }}</p>
                            <p><strong>Rạp:</strong> {{ $booking->showtime->room->cinema->name }}</p>
                            <p><strong>Phòng:</strong> {{ $booking->showtime->room->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Thông tin chiếu</h6>
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
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="">Chọn trạng thái</option>
                                    <option value="PENDING" {{ old('status', $booking->status) == 'PENDING' ? 'selected' : '' }}>Chờ thanh toán</option>
                                    <option value="CONFIRMED" {{ old('status', $booking->status) == 'CONFIRMED' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="CANCELLED" {{ old('status', $booking->status) == 'CANCELLED' ? 'selected' : '' }}>Đã hủy</option>
                                    <option value="EXPIRED" {{ old('status', $booking->status) == 'EXPIRED' ? 'selected' : '' }}>Hết hạn</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Phương thức thanh toán</label>
                                <input type="text" class="form-control @error('payment_method') is-invalid @enderror" 
                                       id="payment_method" name="payment_method" value="{{ old('payment_method', $booking->payment_method) }}">
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Trạng thái thanh toán</label>
                        <input type="text" class="form-control @error('payment_status') is-invalid @enderror" 
                               id="payment_status" name="payment_status" value="{{ old('payment_status', $booking->payment_status) }}">
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary me-2">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật đặt vé
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Hướng dẫn</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Chỉ có thể thay đổi trạng thái
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Cẩn thận khi thay đổi trạng thái
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Xác nhận thông tin trước khi lưu
                    </li>
                    <li>
                        <i class="fas fa-undo text-primary me-2"></i>
                        Có thể hoàn tác thay đổi
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
