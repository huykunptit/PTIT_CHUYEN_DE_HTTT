@extends('layouts.admin')

@section('title', 'Chi tiết phòng chiếu - Admin Panel')
@section('page-title', 'Chi tiết phòng chiếu')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Thông tin phòng</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Rạp:</strong>
                        <p>{{ $room->cinema->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Tên phòng:</strong>
                        <p>{{ $room->name }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Loại:</strong>
                        <p>
                            @if($room->type === 'IMAX')
                                <span class="badge bg-danger">IMAX</span>
                            @elseif($room->type === '4DX')
                                <span class="badge bg-warning text-dark">4DX</span>
                            @elseif($room->type === 'VIP')
                                <span class="badge bg-primary">VIP</span>
                            @else
                                <span class="badge bg-secondary">STANDARD</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Sức chứa:</strong>
                        <p>{{ $room->capacity }} ghế</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Trạng thái:</strong>
                        <p>
                            @if($room->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Tạm dừng</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Số ghế:</strong>
                        <p>{{ $room->seats->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h5>Thao tác</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Sửa
                    </a>
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Showtimes -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Lịch chiếu gần nhất</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Phim</th>
                        <th>Ngày</th>
                        <th>Giờ chiếu</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($room->showtimes->sortBy('date')->take(10) as $showtime)
                    <tr>
                        <td>{{ $showtime->movie->title }}</td>
                        <td>{{ $showtime->date->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</td>
                        <td>
                            @if($showtime->status === 'ACTIVE')
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">{{ $showtime->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Chưa có lịch chiếu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

