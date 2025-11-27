<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .ticket-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #007bff;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎬 Vé xem phim của bạn</h1>
            <p>Mã vé: <strong>{{ $ticket->ticket_code }}</strong></p>
        </div>
        
        <div class="content">
            <p>Xin chào <strong>{{ $ticket->booking->user->name ?? 'Khách hàng' }}</strong>,</p>
            
            <p>Cảm ơn bạn đã đặt vé tại Cinema! Vé xem phim của bạn đã được xác nhận thành công.</p>
            
            <div class="ticket-info">
                <div class="info-row">
                    <span class="info-label">Phim:</span>
                    <span class="info-value">{{ $ticket->booking->showtime->movie->title }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Rạp:</span>
                    <span class="info-value">{{ $ticket->booking->showtime->room->cinema->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phòng:</span>
                    <span class="info-value">{{ $ticket->booking->showtime->room->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ghế:</span>
                    <span class="info-value">{{ $ticket->seat->row }}{{ $ticket->seat->number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày chiếu:</span>
                    <span class="info-value">{{ $ticket->booking->showtime->date->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Giờ chiếu:</span>
                    <span class="info-value">
                        {{ \Carbon\Carbon::parse($ticket->booking->showtime->start_time)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($ticket->booking->showtime->end_time)->format('H:i') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Giá vé:</span>
                    <span class="info-value">{{ number_format($ticket->price, 0, ',', '.') }}₫</span>
                </div>
            </div>
            
            <p><strong>📎 Đính kèm:</strong> File PDF vé xem phim (có QR code để check-in tại rạp)</p>
            
            <p style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
                <strong>⚠️ Lưu ý:</strong> Vui lòng đến rạp trước giờ chiếu ít nhất 15 phút và mang theo mã vé hoặc quét QR code tại cổng.
            </p>
            
            <p>Chúc bạn xem phim vui vẻ! 🎉</p>
        </div>
        
        <div class="footer">
            <p>Cinema - Hệ thống đặt vé xem phim</p>
            <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
        </div>
    </div>
</body>
</html>

