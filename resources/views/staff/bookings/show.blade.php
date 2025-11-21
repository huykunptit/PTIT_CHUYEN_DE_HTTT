@extends('layouts.staff')

@section('title', 'Chi tiết đặt vé - Staff Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-ticket-alt me-2"></i>Chi tiết đặt vé</h2>
    <a href="{{ route('staff.bookings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Quay lại
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin đặt vé</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Mã đặt vé:</th>
                        <td><strong>{{ $booking->booking_code }}</strong></td>
                    </tr>
                    <tr>
                        <th>Khách hàng:</th>
                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $booking->user->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Phim:</th>
                        <td>{{ $booking->showtime->movie->title }}</td>
                    </tr>
                    <tr>
                        <th>Rạp:</th>
                        <td>{{ $booking->showtime->room->cinema->name }}</td>
                    </tr>
                    <tr>
                        <th>Phòng:</th>
                        <td>{{ $booking->showtime->room->name }}</td>
                    </tr>
                    <tr>
                        <th>Ngày chiếu:</th>
                        <td>{{ $booking->showtime->date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Giờ chiếu:</th>
                        <td>{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Tổng tiền:</th>
                        <td><strong class="text-primary">{{ number_format($booking->final_amount, 0, ',', '.') }}₫</strong></td>
                    </tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            <span class="badge bg-{{ $booking->status === 'CONFIRMED' ? 'success' : ($booking->status === 'PENDING' ? 'warning' : 'danger') }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                    </tr>
                </table>

                <form action="{{ route('staff.bookings.update-status', $booking) }}" method="POST" class="mt-3">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Cập nhật trạng thái:</label>
                            <select name="status" class="form-select">
                                <option value="PENDING" {{ $booking->status === 'PENDING' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="CONFIRMED" {{ $booking->status === 'CONFIRMED' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="CANCELLED" {{ $booking->status === 'CANCELLED' ? 'selected' : '' }}>Đã hủy</option>
                                <option value="COMPLETED" {{ $booking->status === 'COMPLETED' ? 'selected' : '' }}>Hoàn thành</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Danh sách vé</h5>
            </div>
            <div class="card-body">
                @foreach($booking->tickets as $ticket)
                <div class="border-bottom pb-2 mb-2">
                    <div><strong>Mã vé:</strong> {{ $ticket->ticket_code }}</div>
                    <div><strong>Ghế:</strong> {{ $ticket->seat->row }}{{ $ticket->seat->number }}</div>
                    <div><strong>Giá:</strong> {{ number_format($ticket->price, 0, ',', '.') }}₫</div>
                    <div>
                        <strong>Trạng thái:</strong>
                        <span class="badge bg-{{ $ticket->status === 'SOLD' ? 'success' : ($ticket->status === 'USED' ? 'info' : 'warning') }}">
                            {{ $ticket->status }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

