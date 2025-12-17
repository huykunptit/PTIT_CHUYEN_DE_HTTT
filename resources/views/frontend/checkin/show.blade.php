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

                    <!-- Tabs để chuyển đổi giữa Quét QR và Nhập mã -->
                    <ul class="nav nav-tabs mb-4" id="checkinTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="scan-tab" data-bs-toggle="tab" data-bs-target="#scan" type="button" role="tab">
                                <i class="fas fa-camera me-2"></i>Quét QR Code
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab">
                                <i class="fas fa-keyboard me-2"></i>Nhập mã thủ công
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="checkinTabContent">
                        <!-- Tab Quét QR Code -->
                        <div class="tab-pane fade show active" id="scan" role="tabpanel">
                            <div class="mb-4 text-center">
                                <i class="fas fa-qrcode text-primary" style="font-size: 4rem;"></i>
                                <p class="text-muted mt-3">Quét mã QR trên vé để check-in</p>
                            </div>

                            <!-- Camera container -->
                            <div class="mb-4">
                                <div id="qr-reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                                <div id="qr-reader-results" class="mt-3"></div>
                            </div>

                            <!-- Hidden form để submit sau khi quét -->
                            <form method="POST" action="{{ route('checkin.checkin') }}" id="scanForm" style="display: none;">
                                @csrf
                                <input type="hidden" name="ticket_code" id="scanned_ticket_code">
                            </form>
                        </div>

                        <!-- Tab Nhập mã thủ công -->
                        <div class="tab-pane fade" id="manual" role="tabpanel">
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

                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>Về trang chủ
                        </a>
                    </div>
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

<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<style>
    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    #ticket_code {
        font-family: 'Courier New', monospace;
    }
    
    #qr-reader {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }
    
    #qr-reader__dashboard_section {
        background: #f8f9fa;
        padding: 15px;
    }
    
    #qr-reader__camera_selection {
        margin-bottom: 10px;
    }
    
    .scanning-indicator {
        text-align: center;
        padding: 10px;
        background: #e3f2fd;
        border-radius: 4px;
        margin-bottom: 10px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ticketInput = document.getElementById('ticket_code');
    let html5QrcodeScanner = null;
    let isScanning = false;
    
    // Auto uppercase cho input thủ công
    if (ticketInput) {
        ticketInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        
        // Only allow alphanumeric
        ticketInput.addEventListener('keypress', function(e) {
            if (!/[A-Z0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete') {
                e.preventDefault();
            }
        });
    }
    
    // Xử lý khi chuyển tab
    const scanTab = document.getElementById('scan-tab');
    const manualTab = document.getElementById('manual-tab');
    const scanPane = document.getElementById('scan');
    const manualPane = document.getElementById('manual');
    
    // Khi click vào tab Quét QR
    scanTab.addEventListener('shown.bs.tab', function() {
        startQRScanner();
    });
    
    // Khi click vào tab Nhập mã
    manualTab.addEventListener('shown.bs.tab', function() {
        stopQRScanner();
    });
    
    // Khởi động scanner khi trang load (nếu đang ở tab scan)
    if (scanPane && scanPane.classList.contains('active')) {
        startQRScanner();
    }
    
    function startQRScanner() {
        if (isScanning || html5QrcodeScanner) {
            return;
        }
        
        isScanning = true;
        const qrReaderElement = document.getElementById('qr-reader');
        
        if (!qrReaderElement) {
            return;
        }
        
        // Tạo scanner instance
        html5QrcodeScanner = new Html5Qrcode("qr-reader");
        
        // Cấu hình scanner
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
            supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
        };
        
        // Bắt đầu quét
        html5QrcodeScanner.start(
            { facingMode: "environment" }, // Ưu tiên camera sau
            config,
            onScanSuccess,
            onScanError
        ).catch(err => {
            console.error("Không thể khởi động camera:", err);
            const resultsDiv = document.getElementById('qr-reader-results');
            if (resultsDiv) {
                resultsDiv.innerHTML = 
                    '<div class="alert alert-danger">Không thể truy cập camera. Vui lòng kiểm tra quyền truy cập camera hoặc sử dụng chế độ nhập mã thủ công.</div>';
            }
            isScanning = false;
        });
    }
    
    function stopQRScanner() {
        if (html5QrcodeScanner && isScanning) {
            html5QrcodeScanner.stop().then(() => {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
                isScanning = false;
                const resultsDiv = document.getElementById('qr-reader-results');
                if (resultsDiv) {
                    resultsDiv.innerHTML = '';
                }
            }).catch(err => {
                console.error("Lỗi khi dừng scanner:", err);
            });
        }
    }
    
    function onScanSuccess(decodedText, decodedResult) {
        // Dừng scanner sau khi quét thành công
        stopQRScanner();
        
        // Làm sạch mã (loại bỏ khoảng trắng, chuyển thành chữ hoa)
        const ticketCode = decodedText.trim().toUpperCase();
        
        // Validate mã vé (10 ký tự)
        if (ticketCode.length !== 10) {
            const resultsDiv = document.getElementById('qr-reader-results');
            if (resultsDiv) {
                resultsDiv.innerHTML = 
                    '<div class="alert alert-warning">Mã QR không hợp lệ. Mã vé phải có 10 ký tự.</div>';
            }
            // Khởi động lại scanner sau 2 giây
            setTimeout(() => {
                if (scanPane && scanPane.classList.contains('active')) {
                    startQRScanner();
                }
            }, 2000);
            return;
        }
        
        // Hiển thị thông báo đang xử lý
        const resultsDiv = document.getElementById('qr-reader-results');
        if (resultsDiv) {
            resultsDiv.innerHTML = 
                '<div class="alert alert-info"><i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý mã vé: <strong>' + ticketCode + '</strong></div>';
        }
        
        // Set giá trị vào hidden input và submit form
        const scannedInput = document.getElementById('scanned_ticket_code');
        const scanForm = document.getElementById('scanForm');
        if (scannedInput && scanForm) {
            scannedInput.value = ticketCode;
            scanForm.submit();
        }
    }
    
    function onScanError(errorMessage) {
        // Không hiển thị lỗi liên tục, chỉ log
        // console.log("Scan error:", errorMessage);
    }
    
    // Cleanup khi rời khỏi trang
    window.addEventListener('beforeunload', function() {
        stopQRScanner();
    });
});
</script>
@endsection

