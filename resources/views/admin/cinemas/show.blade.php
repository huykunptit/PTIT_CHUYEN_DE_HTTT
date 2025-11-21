@extends('layouts.admin')

@section('title', 'Chi tiết rạp chiếu - Admin Panel')
@section('page-title', 'Chi tiết rạp chiếu')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Thông tin rạp</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Tên rạp:</strong>
                        <p>{{ $cinema->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Trạng thái:</strong>
                        <p>
                            @if($cinema->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Tạm dừng</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-8">
                        <strong>Địa chỉ:</strong>
                        <p>{{ $cinema->address }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Khu vực:</strong>
                        <p>
                            @if($cinema->city === 'HN')
                                Hà Nội
                            @else
                                TP. Hồ Chí Minh
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Số điện thoại:</strong>
                        <p>{{ $cinema->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong>
                        <p>{{ $cinema->email ?? '-' }}</p>
                    </div>
                </div>
                @if($cinema->description)
                <div class="mb-3">
                    <strong>Mô tả:</strong>
                    <p>{{ $cinema->description }}</p>
                </div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Số phòng:</strong>
                        <p>{{ $cinema->rooms->count() }} phòng</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Tổng ghế:</strong>
                        <p>{{ $cinema->rooms->sum('capacity') }} ghế</p>
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
                    <a href="{{ route('admin.cinemas.edit', $cinema) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Sửa
                    </a>
                    <a href="{{ route('admin.rooms.index') }}?cinema_id={{ $cinema->id }}" class="btn btn-info">
                        <i class="fas fa-door-open me-2"></i>Xem phòng
                    </a>
                    <a href="{{ route('admin.cinemas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rooms List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Danh sách phòng chiếu</h6>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>Thêm phòng
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Tên phòng</th>
                        <th>Loại</th>
                        <th>Sức chứa</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cinema->rooms as $room)
                    <tr>
                        <td>{{ $room->name }}</td>
                        <td>
                            @if($room->type === 'IMAX')
                                <span class="badge bg-danger">IMAX</span>
                            @elseif($room->type === '4DX')
                                <span class="badge bg-warning text-dark">4DX</span>
                            @elseif($room->type === 'VIP')
                                <span class="badge bg-primary">VIP</span>
                            @else
                                <span class="badge bg-secondary">STANDARD</span>
                            @endif
                        </td>
                        <td>{{ $room->capacity }} ghế</td>
                        <td>
                            @if($room->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Tạm dừng</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.rooms.show', $room) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Chưa có phòng nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

