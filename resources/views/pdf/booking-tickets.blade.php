<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vé xem phim - {{ $booking->booking_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            background: #fff;
        }
        .page-break {
            page-break-after: always;
        }
        .ticket-container {
            max-width: 800px;
            margin: 0 auto 30px;
            padding: 20px;
            border: 3px solid #007bff;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .ticket-content {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }
        .header h1 {
            color: #007bff;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header .subtitle {
            color: #666;
            font-size: 14px;
        }
        .booking-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .booking-info strong {
            color: #007bff;
            font-size: 14px;
        }
        .ticket-code {
            text-align: center;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .ticket-code h2 {
            font-size: 32px;
            letter-spacing: 5px;
            color: #007bff;
            margin: 10px 0;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .info-section {
            margin: 20px 0;
        }
        .info-row {
            display: table;
            width: 100%;
            margin: 10px 0;
        }
        .info-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            color: #666;
        }
        .info-value {
            display: table-cell;
            width: 70%;
            color: #333;
        }
        .divider {
            border-top: 1px dashed #ddd;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #007bff;
            color: #666;
            font-size: 11px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            color: #856404;
        }
        .warning strong {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    @foreach($tickets as $index => $ticket)
    <div class="ticket-container {{ $index < count($tickets) - 1 ? 'page-break' : '' }}">
        <div class="ticket-content">
            <div class="header">
                <h1>🎬 CINEMA TICKET</h1>
                <div class="subtitle">Vé xem phim điện tử</div>
            </div>

            <div class="booking-info">
                <strong>Mã đặt vé: {{ $booking->booking_code }}</strong>
            </div>

            <div class="ticket-code">
                <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Mã vé</div>
                <h2>{{ $ticket->ticket_code }}</h2>
            </div>

            <div class="qr-code">
                @if(isset($ticket->qrCodeBase64))
                    <img src="{{ $ticket->qrCodeBase64 }}" alt="QR Code" style="width: 200px; height: 200px;">
                @else
                    <div style="width: 200px; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; margin: 0 auto; border: 1px solid #ddd;">
                        <div style="text-align: center; color: #666;">
                            <div style="font-size: 24px; margin-bottom: 5px;">QR</div>
                            <div style="font-size: 10px;">{{ $ticket->ticket_code }}</div>
                        </div>
                    </div>
                @endif
                <div style="margin-top: 10px; color: #666; font-size: 11px;">
                    Quét mã QR này tại rạp để check-in
                </div>
            </div>

            <div class="divider"></div>

            <div class="info-section">
                <div class="info-row">
                    <div class="info-label">Phim:</div>
                    <div class="info-value">{{ $booking->showtime->movie->title }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Rạp:</div>
                    <div class="info-value">{{ $booking->showtime->room->cinema->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phòng:</div>
                    <div class="info-value">{{ $booking->showtime->room->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Ghế:</div>
                    <div class="info-value">{{ $ticket->seat->row }}{{ $ticket->seat->number }} ({{ $ticket->seat->type }})</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Ngày chiếu:</div>
                    <div class="info-value">{{ $booking->showtime->date->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Giờ chiếu:</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($booking->showtime->end_time)->format('H:i') }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Giá vé:</div>
                    <div class="info-value">{{ number_format($ticket->price, 0, ',', '.') }}₫</div>
                </div>
                @if($booking->user)
                <div class="info-row">
                    <div class="info-label">Khách hàng:</div>
                    <div class="info-value">{{ $booking->user->name }}</div>
                </div>
                @endif
            </div>

            <div class="divider"></div>

            <div class="warning">
                <strong>⚠️ Lưu ý quan trọng:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    <li>Vui lòng đến rạp trước giờ chiếu ít nhất 15 phút</li>
                    <li>Mang theo mã vé hoặc quét QR code tại cổng</li>
                    <li>Vé này chỉ có giá trị cho suất chiếu đã đặt</li>
                    <li>Không hoàn tiền hoặc đổi vé sau khi đã thanh toán</li>
                </ul>
            </div>

            <div class="footer">
                <div>Cinema - Hệ thống đặt vé xem phim</div>
                <div style="margin-top: 5px;">Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</div>
            </div>
        </div>
    </div>
    @endforeach
</body>
</html>

