@extends('layouts.app')

@section('title', 'Xác thực OTP - Cinema')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-shield-alt text-primary" style="font-size: 3rem;"></i>
                        <h3 class="mt-3">Nhập mã OTP</h3>
                        <p class="text-muted">Mã đã được gửi đến số {{ $phone }}</p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login.phone.verify.post') }}">
                        @csrf

                        <input type="hidden" name="phone" value="{{ $phone }}">

                        <div class="mb-3">
                            <label for="otp" class="form-label">Mã OTP</label>
                            <input type="text" class="form-control text-center fs-4 @error('otp') is-invalid @enderror"
                                   id="otp" name="otp" placeholder="••••••" maxlength="6" required autofocus>
                            @error('otp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-check me-2"></i>Xác nhận
                        </button>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('login.phone') }}" class="text-decoration-none">
                            <i class="fas fa-redo me-1"></i>Gửi lại mã
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

