@extends('layouts.admin')

@section('title', 'Thêm phim mới - Admin Panel')
@section('page-title', 'Thêm phim mới')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thông tin phim</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.movies.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Tên phim <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="duration" class="form-label">Thời lượng (phút) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                       id="duration" name="duration" value="{{ old('duration') }}" required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genre" class="form-label">Thể loại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('genre') is-invalid @enderror" 
                                       id="genre" name="genre" value="{{ old('genre') }}" required>
                                @error('genre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="country" class="form-label">Quốc gia <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" value="{{ old('country') }}" required>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="language" class="form-label">Ngôn ngữ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('language') is-invalid @enderror" 
                                       id="language" name="language" value="{{ old('language') }}" required>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="age_rating" class="form-label">Giới hạn độ tuổi <span class="text-danger">*</span></label>
                                <select class="form-select @error('age_rating') is-invalid @enderror" 
                                        id="age_rating" name="age_rating" required>
                                    <option value="">Chọn độ tuổi</option>
                                    <option value="0" {{ old('age_rating') == '0' ? 'selected' : '' }}>P - Mọi lứa tuổi</option>
                                    <option value="13" {{ old('age_rating') == '13' ? 'selected' : '' }}>13+</option>
                                    <option value="16" {{ old('age_rating') == '16' ? 'selected' : '' }}>16+</option>
                                    <option value="18" {{ old('age_rating') == '18' ? 'selected' : '' }}>18+</option>
                                </select>
                                @error('age_rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="release_date" class="form-label">Ngày phát hành <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('release_date') is-invalid @enderror" 
                                       id="release_date" name="release_date" value="{{ old('release_date') }}" required>
                                @error('release_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rating" class="form-label">Đánh giá (0-10)</label>
                                <input type="number" step="0.1" min="0" max="10" 
                                       class="form-control @error('rating') is-invalid @enderror" 
                                       id="rating" name="rating" value="{{ old('rating', 0) }}">
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="">Chọn trạng thái</option>
                                    <option value="COMING_SOON" {{ old('status') == 'COMING_SOON' ? 'selected' : '' }}>Sắp chiếu</option>
                                    <option value="NOW_SHOWING" {{ old('status') == 'NOW_SHOWING' ? 'selected' : '' }}>Đang chiếu</option>
                                    <option value="ENDED" {{ old('status') == 'ENDED' ? 'selected' : '' }}>Kết thúc</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="trailer_url" class="form-label">Link trailer</label>
                        <input type="url" class="form-control @error('trailer_url') is-invalid @enderror" 
                               id="trailer_url" name="trailer_url" value="{{ old('trailer_url') }}">
                        @error('trailer_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                   value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Phim nổi bật
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary me-2">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu phim
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
                        Điền đầy đủ thông tin phim
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar text-warning me-2"></i>
                        Chọn ngày phát hành và kết thúc
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-star text-success me-2"></i>
                        Đánh giá từ 0-10 điểm
                    </li>
                    <li>
                        <i class="fas fa-flag text-primary me-2"></i>
                        Chọn trạng thái phù hợp
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
