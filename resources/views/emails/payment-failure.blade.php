<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #fef2f2; margin: 0; padding: 0; }
        .wrapper { max-width: 640px; margin: 0 auto; padding: 24px; }
        .card { background: #ffffff; border-radius: 12px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); overflow: hidden; border: 1px solid #fecaca; }
        .header { background: linear-gradient(135deg, #f43f5e 0%, #fb7185 100%); padding: 32px; color: #fff; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; letter-spacing: .5px; }
        .body { padding: 32px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .table th { text-align: left; font-size: 12px; text-transform: uppercase; color: #9f1239; padding: 10px 0 4px; }
        .table td { padding: 8px 0; border-bottom: 1px solid #fee2e2; color: #7f1d1d; }
        .table tr:last-child td { border-bottom: none; }
        .alert { padding: 16px; border-radius: 8px; background: #fef2f2; border: 1px solid #fecaca; color: #7f1d1d; }
        .footer { text-align: center; padding: 24px; font-size: 13px; color: #7f1d1d; }
        .btn { display: inline-block; padding: 12px 24px; background: #ef4444; color: #fff; border-radius: 8px; text-decoration: none; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div style="font-size: 13px; opacity: .8;">Mã đơn hàng</div>
                <h1>{{ $booking->booking_code }}</h1>
                <div style="margin-top: 12px; font-weight: 600;">Thanh toán không thành công</div>
            </div>
            <div class="body">
                <p>Xin chào <strong>{{ $booking->user->name ?? 'Khách hàng' }}</strong>,</p>
                <p>Giao dịch thanh toán của bạn chưa hoàn tất. Vui lòng xem chi tiết bên dưới:</p>

                <div class="alert">
                    <strong>Lý do:</strong> {{ $messageText }}
                </div>

                <table class="table" style="margin-top: 20px;">
                    <tr>
                        <th>Ngày giờ thực hiện</th>
                        <td>{{ now()->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Mã giao dịch</th>
                        <td>{{ $paymentDetails['vnp_TransactionNo'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Số tiền</th>
                        <td>{{ number_format($booking->final_amount, 0, ',', '.') }} VNĐ</td>
                    </tr>
                    <tr>
                        <th>Hình thức</th>
                        <td>{{ $booking->payment_method ?? 'VNPay' }}</td>
                    </tr>
                </table>

                <p>Bạn có thể thử thanh toán lại trước khi vé hết hạn:</p>
                <p>
                    <a class="btn" href="{{ route('payment.index', $booking) }}">
                        Thanh toán lại
                    </a>
                </p>

                <p style="margin-top:24px;">Nếu giao dịch đã trừ tiền nhưng vẫn báo lỗi, vui lòng liên hệ bộ phận hỗ trợ để được kiểm tra.</p>
            </div>
            <div class="footer">
                Cinema · Hotline: 1900 xxxx · {{ config('app.url') }}
            </div>
        </div>
    </div>
</body>
</html>

