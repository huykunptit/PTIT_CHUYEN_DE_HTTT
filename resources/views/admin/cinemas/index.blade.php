@extends('layouts.admin')

@section('title', 'Quản lý rạp - Admin Panel')
@section('page-title', 'Quản lý rạp')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Danh sách rạp chiếu</h5>
    <a href="{{ route('admin.cinemas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Thêm rạp mới
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tên rạp</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cinemas as $cinema)
                    <tr>
                        <td>
                            <strong>{{ $cinema->name }}</strong>
                            @if($cinema->description)
                                <br>
                                <small class="text-muted">{{ Str::limit($cinema->description, 50) }}</small>
                            @endif
                        </td>
                        <td>{{ Str::limit($cinema->address, 50) }}</td>
                        <td>{{ $cinema->phone }}</td>
                        <td>{{ $cinema->email ?? '-' }}</td>
                        <td>
                            @if($cinema->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Tạm dừng</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.cinemas.show', $cinema) }}" 
                                   class="btn btn-sm btn-outline-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.cinemas.edit', $cinema) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.cinemas.destroy', $cinema) }}" 
                                      style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa rạp này?')">
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
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-building fa-2x mb-2"></i><br>
                            Chưa có rạp nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($cinemas->hasPages())
        <div class="d-flex justify-content-center">
            {{ $cinemas->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
