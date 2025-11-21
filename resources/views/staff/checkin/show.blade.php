@extends('layouts.staff')

@section('title', 'Check-in - Staff Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-qrcode me-2"></i>Check-in vé</h2>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-qrcode me-2"></i>Check-in vé xem phim
                </h5>
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

                @if(session('warning'))
                    <div class="alert alert-warning">
                        {{ session('warning') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('staff.checkin.checkin') }}" class="mt-4">
                    @csrf
                    
                    <div class="mb-4 text-center">
                        <i class="fas fa-ticket-alt text-primary" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3">Nhập mã vé để check-in (Staff có thể check-in bất kỳ lúc nào)</p>
                    </div>

                    <div class="mb-4">
                        <label for="ticket_code" class="form-label">
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
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="fas fa-info-circle me-2"></i>Lưu ý cho Staff
                </h6>
                <ul class="text-muted small">
                    <li>Staff có thể check-in vé bất kỳ lúc nào (không cần kiểm tra thời gian)</li>
                    <li>Vé phải ở trạng thái SOLD (đã thanh toán) mới có thể check-in</li>
                    <li>Mỗi vé chỉ được check-in một lần</li>
                    <li>Sau khi check-in, vé sẽ chuyển sang trạng thái USED</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
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

