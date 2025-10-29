@extends('layouts.admin')

@section('title', 'Quản lý đặt vé - Admin Panel')
@section('page-title', 'Quản lý đặt vé')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã đặt vé</th>
                        <th>Khách hàng</th>
                        <th>Phim</th>
                        <th>Rạp</th>
                        <th>Ngày chiếu</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <strong>{{ $booking->booking_code }}</strong>
                            <br>
                            <small class="text-muted">{{ $booking->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td>
                            @if($booking->user)
                                <strong>{{ $booking->user->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $booking->user->email }}</small>
                            @else
                                <span class="text-muted">Khách vãng lai</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $booking->showtime->movie->title }}</strong>
                            <br>
                            <small class="text-muted">{{ $booking->showtime->movie->genre }}</small>
                        </td>
                        <td>
                            {{ $booking->showtime->room->cinema->name }}
                            <br>
                            <small class="text-muted">{{ $booking->showtime->room->name }}</small>
                        </td>
                        <td>
                            {{ $booking->showtime->date->format('d/m/Y') }}
                            <br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($booking->showtime->end_time)->format('H:i') }}
                            </small>
                        </td>
                        <td>
                            <strong>{{ number_format($booking->final_amount, 0, ',', '.') }}₫</strong>
                            @if($booking->discount_amount > 0)
                                <br>
                                <small class="text-success">-{{ number_format($booking->discount_amount, 0, ',', '.') }}₫</small>
                            @endif
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.bookings.show', $booking) }}" 
                                   class="btn btn-sm btn-outline-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.bookings.edit', $booking) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" 
                                      style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa đặt vé này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-ticket-alt fa-2x mb-2"></i><br>
                            Chưa có đặt vé nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($bookings->hasPages())
        <div class="d-flex justify-content-center">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
