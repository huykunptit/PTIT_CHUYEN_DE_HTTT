@extends('layouts.app')

@section('title', 'Đăng nhập bằng số điện thoại - Cinema')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-mobile-alt text-primary" style="font-size: 3rem;"></i>
                        <h3 class="mt-3">Đăng nhập bằng SĐT</h3>
                        <p class="text-muted">Nhập số điện thoại để nhận mã OTP</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login.phone.send') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                            </div>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-paper-plane me-2"></i>Gửi mã OTP
                        </button>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại trang đăng nhập
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

