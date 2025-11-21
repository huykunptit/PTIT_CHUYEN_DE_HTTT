@extends('layouts.staff')

@section('title', 'Check-in thành công - Staff Panel')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-success">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i>Check-in thành công!
                </h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                </div>

                <div class="alert alert-success">
                    <strong>Vé đã được check-in thành công!</strong>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Thông tin vé</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Mã vé:</th>
                                <td><strong>{{ $ticket->ticket_code }}</strong></td>
                            </tr>
                            <tr>
                                <th>Phim:</th>
                                <td>{{ $ticket->booking->showtime->movie->title }}</td>
                            </tr>
                            <tr>
                                <th>Rạp:</th>
                                <td>{{ $ticket->booking->showtime->room->cinema->name }}</td>
                            </tr>
                            <tr>
                                <th>Phòng:</th>
                                <td>{{ $ticket->booking->showtime->room->name }}</td>
                            </tr>
                            <tr>
                                <th>Ghế:</th>
                                <td><strong>{{ $ticket->seat->row }}{{ $ticket->seat->number }}</strong></td>
                            </tr>
                            <tr>
                                <th>Ngày chiếu:</th>
                                <td>{{ $ticket->booking->showtime->date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Giờ chiếu:</th>
                                <td>{{ \Carbon\Carbon::parse($ticket->booking->showtime->start_time)->format('H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Thời gian check-in:</th>
                                <td>{{ $ticket->checked_in_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td><span class="badge bg-success">Đã sử dụng</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('staff.checkin.show') }}" class="btn btn-primary">
                        <i class="fas fa-qrcode me-2"></i>Check-in vé khác
                    </a>
                    <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Về Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

