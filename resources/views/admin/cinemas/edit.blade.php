@extends('layouts.admin')

@section('title', 'Sửa rạp chiếu - Admin Panel')
@section('page-title', 'Sửa rạp chiếu')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.cinemas.update', $cinema) }}">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="name" class="form-label">Tên rạp <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $cinema->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="city" class="form-label">Khu vực <span class="text-danger">*</span></label>
                    <select class="form-select @error('city') is-invalid @enderror" id="city" name="city" required>
                        <option value="HCM" {{ old('city', $cinema->city) === 'HCM' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                        <option value="HN" {{ old('city', $cinema->city) === 'HN' ? 'selected' : '' }}>Hà Nội</option>
                    </select>
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" value="{{ old('phone', $cinema->phone) }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                <textarea class="form-control @error('address') is-invalid @enderror" 
                          id="address" name="address" rows="2" required>{{ old('address', $cinema->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $cinema->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="is_active" class="form-label">Trạng thái</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="1" {{ old('is_active', $cinema->is_active) ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ old('is_active', $cinema->is_active) === false ? 'selected' : '' }}>Tạm dừng</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $cinema->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.cinemas.index') }}" class="btn btn-secondary">
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

