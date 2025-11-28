@extends('layouts.app')

@section('title', 'Check-in thành công - Cinema')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-success text-white text-center">
                <div class="card-body py-5">
                    <i class="fas fa-check-circle" style="font-size: 5rem;"></i>
                    <h2 class="mt-4 mb-3">Check-in thành công!</h2>
                    <p class="lead">Vé của bạn đã được check-in thành công</p>
                </div>
            </div>

            <div class="card bg-light border-secondary mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-ticket-alt me-2"></i>Thông tin vé
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Mã vé:</strong>
                            <p class="text-white fs-5">{{ $ticket->ticket_code }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Ghế:</strong>
                            <p class="text-white fs-5">{{ $ticket->seat->row }}{{ $ticket->seat->number }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Phim:</strong>
                            <p class="text-white">{{ $ticket->booking->showtime->movie->title }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Rạp:</strong>
                            <p class="text-white">{{ $ticket->booking->showtime->room->cinema->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Ngày chiếu:</strong>
                            <p class="text-white">{{ $ticket->booking->showtime->date->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-primary">Giờ chiếu:</strong>
                            <p class="text-white">
                                {{ \Carbon\Carbon::parse($ticket->booking->showtime->start_time)->format('H:i') }}
                            </p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <strong class="text-primary">Thời gian check-in:</strong>
                            <p class="text-white">{{ $ticket->checked_in_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('checkin.show') }}" class="btn btn-outline-primary">
                            <i class="fas fa-qrcode me-2"></i>Check-in vé khác
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

