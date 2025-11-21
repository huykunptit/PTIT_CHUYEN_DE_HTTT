@extends('layouts.admin')

@section('title', 'Sửa phòng chiếu - Admin Panel')
@section('page-title', 'Sửa phòng chiếu')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="cinema_id" class="form-label">Rạp chiếu <span class="text-danger">*</span></label>
                    <select class="form-select @error('cinema_id') is-invalid @enderror" id="cinema_id" name="cinema_id" required>
                        <option value="">-- Chọn rạp --</option>
                        @foreach($cinemas as $cinema)
                            <option value="{{ $cinema->id }}" {{ old('cinema_id', $room->cinema_id) == $cinema->id ? 'selected' : '' }}>
                                {{ $cinema->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('cinema_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="name" class="form-label">Tên phòng <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $room->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="type" class="form-label">Loại phòng <span class="text-danger">*</span></label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="STANDARD" {{ old('type', $room->type) == 'STANDARD' ? 'selected' : '' }}>STANDARD</option>
                        <option value="VIP" {{ old('type', $room->type) == 'VIP' ? 'selected' : '' }}>VIP</option>
                        <option value="IMAX" {{ old('type', $room->type) == 'IMAX' ? 'selected' : '' }}>IMAX</option>
                        <option value="4DX" {{ old('type', $room->type) == '4DX' ? 'selected' : '' }}>4DX</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="capacity" class="form-label">Sức chứa <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                           id="capacity" name="capacity" value="{{ old('capacity', $room->capacity) }}" 
                           min="1" max="500" required>
                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="is_active" class="form-label">Trạng thái</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="1" {{ old('is_active', $room->is_active) ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ old('is_active', $room->is_active) === false ? 'selected' : '' }}>Tạm dừng</option>
                    </select>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

