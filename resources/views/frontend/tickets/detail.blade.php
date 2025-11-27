@extends('layouts.app')

@section('title', 'Chi tiết vé - Cinema')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark border-secondary">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-ticket-alt me-2"></i>Chi tiết vé
                    </h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-primary text-white p-4 rounded d-inline-block mb-3">
                            <h2 class="mb-0" style="letter-spacing: 3px;">{{ $ticket->ticket_code }}</h2>
                        </div>
                        @if($ticket->status === 'SOLD' || $ticket->status === 'USED')
                        <div class="mb-3">
                            <p class="text-white mb-2"><strong>QR Code để check-in:</strong></p>
                            <div class="bg-white p-3 rounded d-inline-block">
                                {!! QrCode::size(200)->generate($ticket->ticket_code) !!}
                            </div>
                            <p class="text-muted small mt-2">Quét mã QR này tại rạp để check-in</p>
                        </div>
                        @endif
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Ghế:</strong>
                            <p class="text-white fs-4">{{ $ticket->seat->row }}{{ $ticket->seat->number }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Giá vé:</strong>
                            <p class="text-white fs-4">{{ number_format($ticket->price, 0, ',', '.') }}₫</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <strong class="text-primary">Phim:</strong>
                            <p class="text-white fs-5">{{ $ticket->booking->showtime->movie->title }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Rạp:</strong>
                            <p class="text-white">{{ $ticket->booking->showtime->room->cinema->name }}</p>
                            <p class="text-muted small">{{ $ticket->booking->showtime->room->cinema->address }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Phòng:</strong>
                            <p class="text-white">{{ $ticket->booking->showtime->room->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Ngày chiếu:</strong>
                            <p class="text-white">{{ $ticket->booking->showtime->date->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Giờ chiếu:</strong>
                            <p class="text-white">
                                {{ \Carbon\Carbon::parse($ticket->booking->showtime->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($ticket->booking->showtime->end_time)->format('H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Mã booking:</strong>
                            <p class="text-white">{{ $ticket->booking->booking_code }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Trạng thái:</strong>
                            <p>
                                @if($ticket->status === 'SOLD')
                                    <span class="badge bg-success fs-6">Đã phát vé</span>
                                @elseif($ticket->status === 'USED')
                                    <span class="badge bg-info fs-6">Đã sử dụng</span>
                                @else
                                    <span class="badge bg-warning fs-6">{{ $ticket->status }}</span>
                                @endif
                            </p>
                        </div>
                        @if($ticket->checked_in_at)
                        <div class="col-md-12 mb-3">
                            <strong class="text-primary">Thời gian check-in:</strong>
                            <p class="text-white">{{ $ticket->checked_in_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Lưu ý:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Vui lòng đến rạp 30 phút trước giờ chiếu</li>
                            <li>Chuẩn bị mã vé <strong>{{ $ticket->ticket_code }}</strong> để check-in</li>
                            <li>Mỗi vé chỉ được check-in một lần</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('tickets.print', $ticket->id) }}" class="btn btn-success" target="_blank">
                            <i class="fas fa-print me-2"></i>In vé PDF
                        </a>
                        <a href="{{ route('checkin.show') }}" class="btn btn-primary">
                            <i class="fas fa-qrcode me-2"></i>Check-in vé
                        </a>
                        <a href="{{ route('tickets.show', $ticket->booking->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Về danh sách vé
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

