@extends('layouts.admin')

@section('title', 'Quản lý lịch chiếu - Admin Panel')
@section('page-title', 'Quản lý lịch chiếu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Danh sách lịch chiếu</h5>
    <a href="{{ route('admin.showtimes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tạo lịch chiếu mới
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Phim</th>
                        <th>Rạp</th>
                        <th>Phòng</th>
                        <th>Ngày</th>
                        <th>Giờ chiếu</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($showtimes as $showtime)
                    <tr>
                        <td>
                            <strong>{{ $showtime->movie->title }}</strong>
                            <br>
                            <small class="text-muted">{{ $showtime->movie->genre }}</small>
                        </td>
                        <td>{{ $showtime->room->cinema->name }}</td>
                        <td>{{ $showtime->room->name }}</td>
                        <td>{{ $showtime->date->format('d/m/Y') }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}
                        </td>
                        <td>
                            @switch($showtime->status)
                                @case('ACTIVE')
                                    <span class="badge bg-success">Hoạt động</span>
                                    @break
                                @case('CANCELLED')
                                    <span class="badge bg-danger">Đã hủy</span>
                                    @break
                                @case('COMPLETED')
                                    <span class="badge bg-secondary">Hoàn thành</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.showtimes.show', $showtime) }}" 
                                   class="btn btn-sm btn-outline-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.showtimes.edit', $showtime) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.showtimes.destroy', $showtime) }}" 
                                      style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa lịch chiếu này?')">
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
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-clock fa-2x mb-2"></i><br>
                            Chưa có lịch chiếu nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($showtimes->hasPages())
        <div class="d-flex justify-content-center">
            {{ $showtimes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
