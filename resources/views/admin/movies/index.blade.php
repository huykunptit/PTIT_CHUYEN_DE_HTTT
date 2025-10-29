@extends('layouts.admin')

@section('title', 'Quản lý phim - Admin Panel')
@section('page-title', 'Quản lý phim')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Danh sách phim</h5>
    <a href="{{ route('admin.movies.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Thêm phim mới
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Poster</th>
                        <th>Tên phim</th>
                        <th>Thể loại</th>
                        <th>Trạng thái</th>
                        <th>Đánh giá</th>
                        <th>Ngày phát hành</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movies as $movie)
                    <tr>
                        <td>
                            <div class="bg-dark d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 80px; border-radius: 4px;">
                                <i class="fas fa-film text-light"></i>
                            </div>
                        </td>
                        <td>
                            <strong>{{ $movie->title }}</strong>
                            <br>
                            <small class="text-muted">{{ Str::limit($movie->description, 50) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $movie->genre }}</span>
                        </td>
                        <td>
                            @switch($movie->status)
                                @case('NOW_SHOWING')
                                    <span class="badge bg-success">Đang chiếu</span>
                                    @break
                                @case('COMING_SOON')
                                    <span class="badge bg-info">Sắp chiếu</span>
                                    @break
                                @case('ENDED')
                                    <span class="badge bg-secondary">Kết thúc</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $movie->rating/2 ? '' : '-o' }}"></i>
                                @endfor
                                <span class="ms-1">{{ $movie->rating }}</span>
                            </div>
                        </td>
                        <td>{{ $movie->release_date->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.movies.show', $movie) }}" 
                                   class="btn btn-sm btn-outline-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.movies.edit', $movie) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.movies.destroy', $movie) }}" 
                                      style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa phim này?')">
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
                            <i class="fas fa-film fa-2x mb-2"></i><br>
                            Chưa có phim nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($movies->hasPages())
        <div class="d-flex justify-content-center">
            {{ $movies->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
