@extends('layouts.admin')

@section('title', 'Tạo lịch chiếu mới - Admin Panel')
@section('page-title', 'Tạo lịch chiếu mới')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thông tin lịch chiếu</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.showtimes.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="movie_id" class="form-label">Phim <span class="text-danger">*</span></label>
                                <select class="form-select @error('movie_id') is-invalid @enderror" 
                                        id="movie_id" name="movie_id" required>
                                    <option value="">Chọn phim</option>
                                    @foreach($movies as $movie)
                                        <option value="{{ $movie->id }}" {{ old('movie_id') == $movie->id ? 'selected' : '' }}>
                                            {{ $movie->title }} ({{ $movie->duration }} phút)
                                        </option>
                                    @endforeach
                                </select>
                                @error('movie_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="room_id" class="form-label">Phòng chiếu <span class="text-danger">*</span></label>
                                <select class="form-select @error('room_id') is-invalid @enderror" 
                                        id="room_id" name="room_id" required>
                                    <option value="">Chọn phòng chiếu</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->cinema->name }} - {{ $room->name }} ({{ $room->capacity }} chỗ)
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="date" class="form-label">Ngày chiếu <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                       id="date" name="date" value="{{ old('date') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                       id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="end_time" class="form-label">Giờ kết thúc <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                       id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="">Chọn trạng thái</option>
                            <option value="ACTIVE" {{ old('status') == 'ACTIVE' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="CANCELLED" {{ old('status') == 'CANCELLED' ? 'selected' : '' }}>Đã hủy</option>
                            <option value="COMPLETED" {{ old('status') == 'COMPLETED' ? 'selected' : '' }}>Hoàn thành</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.showtimes.index') }}" class="btn btn-secondary me-2">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Tạo lịch chiếu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Hướng dẫn</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Chọn phim và phòng chiếu
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar text-warning me-2"></i>
                        Chọn ngày và giờ chiếu
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-clock text-success me-2"></i>
                        Hệ thống sẽ kiểm tra xung đột thời gian
                    </li>
                    <li>
                        <i class="fas fa-check-circle text-primary me-2"></i>
                        Chọn trạng thái phù hợp
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Lưu ý</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Kiểm tra xung đột thời gian
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar-check text-info me-2"></i>
                        Ngày chiếu phải từ hôm nay trở đi
                    </li>
                    <li>
                        <i class="fas fa-clock text-danger me-2"></i>
                        Giờ kết thúc phải sau giờ bắt đầu
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    startTimeInput.addEventListener('change', function() {
        if (this.value) {
            const startTime = new Date('2000-01-01 ' + this.value);
            const endTime = new Date(startTime.getTime() + 2 * 60 * 60 * 1000); // +2 hours
            endTimeInput.value = endTime.toTimeString().slice(0, 5);
        }
    });
});
</script>
@endsection
