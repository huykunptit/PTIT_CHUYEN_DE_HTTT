@extends('layouts.admin')

@section('title', 'Thêm rạp mới - Admin Panel')
@section('page-title', 'Thêm rạp mới')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thông tin rạp chiếu</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.cinemas.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên rạp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="city" class="form-label">Khu vực <span class="text-danger">*</span></label>
                                <select class="form-select @error('city') is-invalid @enderror" id="city" name="city" required>
                                    <option value="HCM" {{ old('city', 'HCM') === 'HCM' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                                    <option value="HN" {{ old('city') === 'HN' ? 'selected' : '' }}>Hà Nội</option>
                                </select>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Rạp đang hoạt động
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.cinemas.index') }}" class="btn btn-secondary me-2">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu rạp
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
                        Điền đầy đủ thông tin rạp
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt text-warning me-2"></i>
                        Địa chỉ chi tiết để khách hàng dễ tìm
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone text-success me-2"></i>
                        Số điện thoại liên hệ
                    </li>
                    <li>
                        <i class="fas fa-check-circle text-primary me-2"></i>
                        Chọn trạng thái hoạt động
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
