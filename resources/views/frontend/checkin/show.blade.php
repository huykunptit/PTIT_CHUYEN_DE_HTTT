@extends('layouts.app')

@section('title', 'Check-in - Cinema')

@section('content')
<div class="container py-5" style="min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-light border-secondary">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-qrcode me-2"></i>Check-in vé xem phim
                    </h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('checkin.checkin') }}" class="mt-4">
                        @csrf
                        
                        <div class="mb-4 text-center">
                            <i class="fas fa-ticket-alt text-primary" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Nhập mã vé của bạn để check-in</p>
                        </div>

                        <div class="mb-4">
                            <label for="ticket_code" class="form-label text-white">
                                <strong>Mã vé</strong> <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg text-center @error('ticket_code') is-invalid @enderror" 
                                   id="ticket_code" 
                                   name="ticket_code" 
                                   placeholder="Nhập mã vé (VD: TK12345678)"
                                   value="{{ old('ticket_code') }}"
                                   maxlength="10"
                                   required
                                   autofocus
                                   style="letter-spacing: 2px; font-weight: bold; text-transform: uppercase;">
                            @error('ticket_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Mã vé bao gồm 10 ký tự (VD: TK12345678)
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle me-2"></i>Check-in
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Về trang chủ
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card bg-light border-secondary mt-4">
                <div class="card-body">
                    <h6 class="text-white mb-3">
                        <i class="fas fa-info-circle me-2"></i>Thông tin check-in
                    </h6>
                    <ul class="text-muted small">
                        <li>Check-in chỉ có thể thực hiện 30 phút trước giờ chiếu</li>
                        <li>Vé có thể được check-in tối đa 3 giờ sau giờ chiếu</li>
                        <li>Mỗi vé chỉ được check-in một lần</li>
                        <li>Vui lòng chuẩn bị mã vé trước khi đến rạp</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    #ticket_code {
        font-family: 'Courier New', monospace;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ticketInput = document.getElementById('ticket_code');
    
    // Auto uppercase
    ticketInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    // Only allow alphanumeric
    ticketInput.addEventListener('keypress', function(e) {
        if (!/[A-Z0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete') {
            e.preventDefault();
        }
    });
});
</script>
@endsection

