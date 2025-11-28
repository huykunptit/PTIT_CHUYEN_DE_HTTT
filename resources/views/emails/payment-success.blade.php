<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .wrapper { max-width: 640px; margin: 0 auto; padding: 24px; }
        .card { background: #ffffff; border-radius: 12px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); overflow: hidden; }
        .header { background: linear-gradient(135deg, #0052d4 0%, #65c7f7 50%, #9cecfb 100%); padding: 32px; color: #fff; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; letter-spacing: .5px; }
        .body { padding: 32px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .table th { text-align: left; font-size: 12px; text-transform: uppercase; color: #6b7280; padding: 10px 0 4px; }
        .table td { padding: 8px 0; border-bottom: 1px solid #e5e7eb; color: #111827; }
        .table tr:last-child td { border-bottom: none; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 999px; background: #f97316; color: #fff; font-weight: 600; }
        .footer { text-align: center; padding: 24px; font-size: 13px; color: #6b7280; }
        .btn { display: inline-block; padding: 12px 24px; background: #2563eb; color: #fff; border-radius: 8px; text-decoration: none; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div style="font-size: 13px; opacity: .8;">Mã đơn hàng</div>
                <h1>{{ $booking->booking_code }}</h1>
                <div class="badge">Thanh toán thành công</div>
            </div>
            <div class="body">
                <p>Xin chào <strong>{{ $booking->user->name ?? 'Khách hàng' }}</strong>,</p>
                <p>Thanh toán cho đơn hàng của bạn đã được xử lý thành công. Dưới đây là thông tin chi tiết giao dịch:</p>

                <table class="table">
                    <tr>
                        <th>Ngày giờ giao dịch</th>
                        <td>{{ now()->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Mã giao dịch</th>
                        <td>{{ $paymentDetails['vnp_TransactionNo'] ?? ($booking->payment_details['transaction_id'] ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <th>Hình thức thanh toán</th>
                        <td>{{ $booking->payment_method ?? 'VNPay' }}</td>
                    </tr>
                    <tr>
                        <th>Tổng tiền vé</th>
                        <td>{{ number_format($booking->total_amount, 0, ',', '.') }} VNĐ</td>
                    </tr>
                    <tr>
                        <th>Khuyến mại</th>
                        <td>{{ number_format($booking->discount_amount, 0, ',', '.') }} VNĐ</td>
                    </tr>
                    <tr>
                        <th>Số tiền thanh toán</th>
                        <td><strong>{{ number_format($booking->final_amount, 0, ',', '.') }} VNĐ</strong></td>
                    </tr>
                </table>

                <h3>Thông tin vé</h3>
                <table class="table">
                    <tr>
                        <th>Phim</th>
                        <td>{{ $booking->showtime->movie->title }}</td>
                    </tr>
                    <tr>
                        <th>Rạp / Phòng</th>
                        <td>{{ $booking->showtime->room->cinema->name }} - {{ $booking->showtime->room->name }}</td>
                    </tr>
                    <tr>
                        <th>Suất chiếu</th>
                        <td>{{ $booking->showtime->date->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Ghế</th>
                        <td>
                            @foreach($booking->tickets as $ticket)
                                {{ $ticket->seat->row }}{{ $ticket->seat->number }}@if(!$loop->last), @endif
                            @endforeach
                        </td>
                    </tr>
                </table>

                <p>Bạn có thể xem và in vé tại đường dẫn dưới đây:</p>
                <p>
                    <a class="btn" href="{{ route('tickets.show', ['booking' => $booking->id]) }}">
                        Xem vé của tôi
                    </a>
                </p>

                <p style="margin-top:24px;">Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi. Chúc bạn có trải nghiệm xem phim tuyệt vời! 🎬</p>
            </div>
            <div class="footer">
                Cinema · Hotline: 1900 xxxx · {{ config('app.url') }}
            </div>
        </div>
    </div>
</body>
</html>

