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
            
            <!-- Seat Configuration Section -->
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chair me-2"></i>Cấu hình ghế
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Lưu ý:</strong> Việc tạo lại ghế sẽ xóa tất cả ghế hiện có và tạo mới. Chỉ thực hiện khi chưa có vé nào được bán.
                    </div>
                    
                    <!-- Layout Configuration -->
                    <h6 class="mb-3">Cấu hình layout</h6>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="num_rows" class="form-label">Số hàng ghế <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="num_rows" name="num_rows" 
                                   value="{{ old('num_rows', ceil($room->capacity / 18)) }}" min="1" max="30" required>
                            <small class="text-muted">Số hàng từ A đến Z</small>
                        </div>
                        <div class="col-md-4">
                            <label for="seats_per_row" class="form-label">Số ghế mỗi hàng <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="seats_per_row" name="seats_per_row" 
                                   value="{{ old('seats_per_row', 18) }}" min="1" max="30" required>
                            <small class="text-muted">Số ghế trên mỗi hàng</small>
                        </div>
                        <div class="col-md-4">
                            <label for="total_seats" class="form-label">Tổng số ghế</label>
                            <input type="number" class="form-control" id="total_seats" readonly>
                            <small class="text-muted">Tự động tính</small>
                        </div>
                    </div>
                    
                    <!-- Seat Type Configuration -->
                    <h6 class="mb-3">Chọn loại ghế</h6>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="btn-group mb-3" role="group">
                                <button type="button" class="btn btn-outline-secondary active" id="selectStandard" data-seat-type="standard">
                                    <i class="fas fa-square me-2" style="color: #f8f9fa;"></i>Ghế thường
                                </button>
                                <button type="button" class="btn btn-outline-warning" id="selectVip" data-seat-type="vip">
                                    <i class="fas fa-square me-2" style="color: #ffc107;"></i>Ghế VIP
                                </button>
                                <button type="button" class="btn btn-outline-danger" id="selectCouple" data-seat-type="couple">
                                    <i class="fas fa-square me-2" style="color: #e83e8c;"></i>Ghế đôi
                                </button>
                                <button type="button" class="btn btn-outline-info" id="selectMode" data-mode="single">
                                    <i class="fas fa-mouse-pointer me-2"></i>Chọn từng ghế
                                </button>
                                <button type="button" class="btn btn-outline-success" id="selectRowMode" data-mode="row">
                                    <i class="fas fa-grip-lines me-2"></i>Chọn cả hàng
                                </button>
                            </div>
                            <div class="alert alert-info mb-0">
                                <small>
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Hướng dẫn:</strong> Chọn loại ghế ở trên, sau đó click vào ghế trong preview để đặt loại. 
                                    Hoặc chọn "Chọn cả hàng" và click vào label hàng để chọn cả hàng.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden inputs for counts (will be auto-filled) -->
                    <input type="hidden" id="standard_count" name="standard_count" value="0">
                    <input type="hidden" id="vip_count" name="vip_count" value="0">
                    <input type="hidden" id="couple_count" name="couple_count" value="0">
                    
                    <!-- VIP Row Configuration -->
                    <h6 class="mb-3">Vị trí hàng VIP</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="vip_start_row" class="form-label">Hàng VIP bắt đầu</label>
                            <select class="form-select" id="vip_start_row" name="vip_start_row">
                                <option value="">-- Tự động --</option>
                                @foreach(range('A', 'Z') as $letter)
                                    <option value="{{ $letter }}">{{ $letter }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hàng bắt đầu đặt ghế VIP (mặc định: hàng giữa)</small>
                        </div>
                        <div class="col-md-6">
                            <label for="vip_end_row" class="form-label">Hàng VIP kết thúc</label>
                            <select class="form-select" id="vip_end_row" name="vip_end_row">
                                <option value="">-- Tự động --</option>
                                @foreach(range('A', 'Z') as $letter)
                                    <option value="{{ $letter }}">{{ $letter }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hàng kết thúc đặt ghế VIP</small>
                        </div>
                    </div>
                    
                    <!-- Couple Seat Configuration -->
                    <h6 class="mb-3">Vị trí ghế đôi</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="couple_position" class="form-label">Vị trí ghế đôi</label>
                            <select class="form-select" id="couple_position" name="couple_position">
                                <option value="end">Cuối mỗi hàng</option>
                                <option value="last_row">Hàng cuối cùng</option>
                                <option value="both">Cả hai</option>
                            </select>
                            <small class="text-muted">Vị trí đặt ghế đôi</small>
                        </div>
                        <div class="col-md-6">
                            <label for="couple_per_row" class="form-label">Số ghế đôi mỗi hàng (nếu chọn cuối hàng)</label>
                            <input type="number" class="form-control" id="couple_per_row" name="couple_per_row" 
                                   value="{{ old('couple_per_row', 2) }}" min="1" max="5">
                            <small class="text-muted">Số ghế đôi ở cuối mỗi hàng</small>
                        </div>
                    </div>
                    
                    <!-- Pricing Configuration -->
                    <h6 class="mb-3">Giá vé</h6>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="standard_price" class="form-label">Giá ghế thường (₫)</label>
                            <input type="number" class="form-control" id="standard_price" name="standard_price" 
                                   value="{{ old('standard_price', 80000) }}" min="0" step="1000">
                        </div>
                        <div class="col-md-4">
                            <label for="vip_price" class="form-label">Giá ghế VIP (₫)</label>
                            <input type="number" class="form-control" id="vip_price" name="vip_price" 
                                   value="{{ old('vip_price', 130000) }}" min="0" step="1000">
                        </div>
                        <div class="col-md-4">
                            <label for="couple_price" class="form-label">Giá ghế đôi (₫)</label>
                            <input type="number" class="form-control" id="couple_price" name="couple_price" 
                                   value="{{ old('couple_price', 160000) }}" min="0" step="1000">
                        </div>
                    </div>
                    
                    <!-- Generate Seats Option -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="generate_seats" name="generate_seats" value="1">
                        <label class="form-check-label" for="generate_seats">
                            <strong>Tạo lại ghế tự động</strong>
                        </label>
                        <small class="d-block text-muted">Chọn để tạo lại tất cả ghế theo cấu hình trên</small>
                    </div>
                </div>
            </div>
            
            <!-- Preview Section -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-eye me-2"></i>Preview Layout Ghế
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="d-flex gap-3 mb-3 flex-wrap">
                                <div class="d-flex align-items-center">
                                    <div class="seat-preview seat-standard me-2"></div>
                                    <span>Ghế thường</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="seat-preview seat-vip me-2"></div>
                                    <span>Ghế VIP</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="seat-preview seat-couple me-2"></div>
                                    <span>Ghế đôi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="seat-preview-container">
                        <div class="text-center mb-3">
                            <div class="screen-preview">MÀN HÌNH</div>
                        </div>
                        <div id="seatPreview" class="seat-layout-preview">
                            <p class="text-center text-muted">Nhập thông tin để xem preview</p>
                        </div>
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <small class="text-muted">Tổng ghế: <strong id="previewTotal">0</strong></small>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Ghế thường: <strong id="previewStandard" class="text-secondary">0</strong></small>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Ghế VIP: <strong id="previewVip" class="text-warning">0</strong></small>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Ghế đôi: <strong id="previewCouple" class="text-danger">0</strong></small>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="resetSeats">
                                    <i class="fas fa-redo me-2"></i>Reset tất cả về ghế thường
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="autoFill">
                                    <i class="fas fa-magic me-2"></i>Tự động điền theo cấu hình
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
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

<style>
.seat-preview-container {
    max-height: 600px;
    overflow: auto;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.screen-preview {
    background: #000;
    color: #fff;
    padding: 10px 60px;
    display: inline-block;
    border-radius: 50px 50px 0 0;
    font-weight: bold;
    margin-bottom: 20px;
}

.seat-layout-preview {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: center;
}

.seat-row-preview {
    display: flex;
    gap: 4px;
    align-items: center;
}

.row-label {
    width: 30px;
    text-align: center;
    font-weight: bold;
    font-size: 14px;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.row-label:hover {
    background-color: #e9ecef;
}

.row-label.selecting {
    background-color: #cfe2ff;
}

.seat-preview {
    width: 30px;
    height: 30px;
    border: 1px solid #ddd;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s;
    user-select: none;
}

.seat-preview:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    z-index: 10;
    position: relative;
}

.seat-preview.seat-standard {
    background-color: #f8f9fa;
    color: #495057;
}

.seat-preview.seat-vip {
    background-color: #ffc107;
    color: #000;
}

.seat-preview.seat-couple {
    background-color: #e83e8c;
    color: white;
}

.seat-preview.seat-empty {
    background-color: transparent;
    border: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const numRows = document.getElementById('num_rows');
    const seatsPerRow = document.getElementById('seats_per_row');
    const totalSeats = document.getElementById('total_seats');
    const standardCount = document.getElementById('standard_count');
    const vipCount = document.getElementById('vip_count');
    const coupleCount = document.getElementById('couple_count');
    const vipStartRow = document.getElementById('vip_start_row');
    const vipEndRow = document.getElementById('vip_end_row');
    const couplePosition = document.getElementById('couple_position');
    const couplePerRow = document.getElementById('couple_per_row');
    const seatPreview = document.getElementById('seatPreview');
    
    // Seat selection state
    let selectedSeatType = 'standard';
    let selectionMode = 'single'; // 'single' or 'row'
    let seatData = {}; // Store seat types: { 'A1': 'vip', 'A2': 'couple', ... }
    
    // Seat type buttons
    const selectStandard = document.getElementById('selectStandard');
    const selectVip = document.getElementById('selectVip');
    const selectCouple = document.getElementById('selectCouple');
    const selectMode = document.getElementById('selectMode');
    const selectRowMode = document.getElementById('selectRowMode');
    const resetSeats = document.getElementById('resetSeats');
    const autoFill = document.getElementById('autoFill');
    
    // Set active seat type
    [selectStandard, selectVip, selectCouple].forEach(btn => {
        btn.addEventListener('click', function() {
            [selectStandard, selectVip, selectCouple].forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedSeatType = this.dataset.seatType;
        });
    });
    
    // Set selection mode
    if (selectMode) {
        selectMode.addEventListener('click', function() {
            selectionMode = 'single';
            selectMode.classList.add('active');
            if (selectRowMode) selectRowMode.classList.remove('active');
        });
    }
    
    if (selectRowMode) {
        selectRowMode.addEventListener('click', function() {
            selectionMode = 'row';
            selectRowMode.classList.add('active');
            if (selectMode) selectMode.classList.remove('active');
        });
    }
    
    // Reset all seats
    if (resetSeats) {
        resetSeats.addEventListener('click', function() {
            const rows = parseInt(numRows.value) || 0;
            const seats = parseInt(seatsPerRow.value) || 0;
            seatData = {};
            for (let r = 0; r < rows; r++) {
                const row = String.fromCharCode(65 + r);
                for (let s = 1; s <= seats; s++) {
                    seatData[`${row}${s}`] = 'standard';
                }
            }
            updatePreview();
        });
    }
    
    // Auto fill based on configuration
    if (autoFill) {
        autoFill.addEventListener('click', function() {
            seatData = {};
            updatePreview(true); // Force auto calculation
        });
    }
    
    // Listen to layout changes
    const inputs = [numRows, seatsPerRow, vipStartRow, vipEndRow, couplePosition, couplePerRow];
    
    inputs.forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                seatData = {}; // Reset when layout changes
                updatePreview();
            });
            input.addEventListener('change', function() {
                seatData = {}; // Reset when layout changes
                updatePreview();
            });
        }
    });
    
    function calculateTotal() {
        const rows = parseInt(numRows.value) || 0;
        const seats = parseInt(seatsPerRow.value) || 0;
        totalSeats.value = rows * seats;
        updatePreview();
    }
    
    numRows.addEventListener('input', calculateTotal);
    seatsPerRow.addEventListener('input', calculateTotal);
    calculateTotal();
    
    function updatePreview(forceAuto = false) {
        const rows = parseInt(numRows.value) || 0;
        const seats = parseInt(seatsPerRow.value) || 0;
        const total = rows * seats;
        
        if (rows === 0 || seats === 0) {
            seatPreview.innerHTML = '<p class="text-center text-muted">Nhập thông tin để xem preview</p>';
            updateCounts(0, 0, 0, 0);
            return;
        }
        
        // Generate row letters
        const rowLetters = Array.from({length: rows}, (_, i) => String.fromCharCode(65 + i));
        
        // Auto calculate VIP rows
        let vipStart = vipStartRow.value;
        let vipEnd = vipEndRow.value;
        
        if (!vipStart || !vipEnd) {
            const midPoint = Math.floor(rows / 2);
            const startIdx = Math.max(2, midPoint - 2);
            const endIdx = Math.min(rows - 1, midPoint + 2);
            vipStart = rowLetters[startIdx];
            vipEnd = rowLetters[endIdx];
        }
        
        const vipStartIdx = rowLetters.indexOf(vipStart);
        const vipEndIdx = rowLetters.indexOf(vipEnd);
        const cpPosition = couplePosition.value || 'end';
        const cpPerRow = parseInt(couplePerRow.value) || 2;
        
        // Initialize or auto-fill seat data
        if (forceAuto || Object.keys(seatData).length === 0) {
            seatData = {};
            const seatList = [];
            
            // Create seat list with priorities
            rowLetters.forEach((row, rowIdx) => {
                const isVipRow = rowIdx >= vipStartIdx && rowIdx <= vipEndIdx;
                const isLastRow = rowIdx === rows - 1;
                
                for (let col = 1; col <= seats; col++) {
                    const isCouplePos = (cpPosition === 'end' && col > (seats - cpPerRow)) ||
                                       (cpPosition === 'last_row' && isLastRow) ||
                                       (cpPosition === 'both' && (isLastRow || col > (seats - cpPerRow)));
                    
                    seatList.push({
                        key: `${row}${col}`,
                        row: row,
                        rowIdx: rowIdx,
                        col: col,
                        isVipRow: isVipRow,
                        isCouplePos: isCouplePos,
                        priority: isCouplePos ? 1 : (isVipRow ? 2 : 3)
                    });
                }
            });
            
            // Sort by priority
            seatList.sort((a, b) => {
                if (a.priority !== b.priority) return a.priority - b.priority;
                if (a.rowIdx !== b.rowIdx) return a.rowIdx - b.rowIdx;
                return a.col - b.col;
            });
            
            // Auto assign
            let currentCouple = 0;
            let currentVip = 0;
            const maxCouple = Math.min(Math.floor(total * 0.15), 
                cpPosition === 'end' ? rows * cpPerRow : 
                cpPosition === 'last_row' ? seats : 
                (rows - 1) * cpPerRow + seats);
            const maxVip = Math.min(Math.floor(total * 0.4), 
                (vipEndIdx - vipStartIdx + 1) * seats);
            
            seatList.forEach(seat => {
                if (seat.isCouplePos && currentCouple < maxCouple) {
                    seatData[seat.key] = 'couple';
                    currentCouple++;
                } else if (seat.isVipRow && !seat.isCouplePos && currentVip < maxVip) {
                    seatData[seat.key] = 'vip';
                    currentVip++;
                } else {
                    seatData[seat.key] = 'standard';
                }
            });
        } else {
            // Ensure all seats exist in seatData
            rowLetters.forEach((row) => {
                for (let col = 1; col <= seats; col++) {
                    const key = `${row}${col}`;
                    if (!seatData[key]) {
                        seatData[key] = 'standard';
                    }
                }
            });
        }
        
        // Generate HTML
        let html = '';
        let currentStd = 0;
        let currentVip = 0;
        let currentCouple = 0;
        
        rowLetters.forEach((row, rowIdx) => {
            html += '<div class="seat-row-preview">';
            html += `<div class="row-label" data-row="${row}">${row}</div>`;
            
            for (let col = 1; col <= seats; col++) {
                const key = `${row}${col}`;
                const seatType = seatData[key] || 'standard';
                
                if (seatType === 'standard') currentStd++;
                else if (seatType === 'vip') currentVip++;
                else if (seatType === 'couple') currentCouple++;
                
                html += `<div class="seat-preview seat-${seatType}" data-seat="${key}" title="${key} - ${seatType.toUpperCase()}">${col}</div>`;
            }
            
            html += '</div>';
        });
        
        seatPreview.innerHTML = html;
        
        // Update hidden inputs
        standardCount.value = currentStd;
        vipCount.value = currentVip;
        coupleCount.value = currentCouple;
        
        updateCounts(total, currentStd, currentVip, currentCouple);
        
        // Attach click handlers
        attachClickHandlers();
    }
    
    function attachClickHandlers() {
        // Seat click handlers
        document.querySelectorAll('.seat-preview').forEach(seat => {
            seat.addEventListener('click', function() {
                const seatKey = this.dataset.seat;
                seatData[seatKey] = selectedSeatType;
                updatePreview();
            });
        });
        
        // Row label click handlers
        document.querySelectorAll('.row-label').forEach(label => {
            label.addEventListener('click', function() {
                if (selectionMode === 'row') {
                    const row = this.dataset.row;
                    const seats = parseInt(seatsPerRow.value) || 0;
                    for (let col = 1; col <= seats; col++) {
                        seatData[`${row}${col}`] = selectedSeatType;
                    }
                    updatePreview();
                }
            });
        });
    }
    
    function updateCounts(total, standard, vip, couple) {
        document.getElementById('previewTotal').textContent = total;
        document.getElementById('previewStandard').textContent = standard;
        document.getElementById('previewVip').textContent = vip;
        document.getElementById('previewCouple').textContent = couple;
    }
    
    // Initial preview
    updatePreview();
});
</script>
@endsection

