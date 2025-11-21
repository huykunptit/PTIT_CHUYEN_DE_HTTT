<?php

namespace App\Services;

use Illuminate\Http\Request;

class VnPayService
{
    protected $vnp_TmnCode;
    protected $vnp_HashSecret;
    protected $vnp_Url;
    protected $vnp_Returnurl;
    protected $vnp_apiUrl;

    public function __construct()
    {
        $this->vnp_TmnCode = config('services.vnpay.tmn_code');
        $this->vnp_HashSecret = config('services.vnpay.hash_secret');
        $this->vnp_Url = config('services.vnpay.url');
        $this->vnp_Returnurl = config('services.vnpay.return_url');
        $this->vnp_apiUrl = str_replace('/paymentv2/vpcpay.html', '/merchant_webapi/merchant.html', $this->vnp_Url);
    }

    /**
     * Tạo URL thanh toán VNPay
     *
     * @param array $params
     * @return string
     */
    public function createPaymentUrl(array $params): string
    {
        $vnp_TxnRef = $params['vnp_TxnRef']; // Mã đơn hàng
        $vnp_Amount = $params['vnp_Amount']; // Số tiền
        $vnp_Locale = $params['vnp_Locale'] ?? 'vn'; // Ngôn ngữ
        $vnp_BankCode = $params['vnp_BankCode'] ?? ''; // Mã ngân hàng
        $vnp_IpAddr = $params['vnp_IpAddr'] ?? $_SERVER['REMOTE_ADDR']; // IP khách hàng
        $vnp_OrderInfo = $params['vnp_OrderInfo'] ?? 'Thanh toan don hang'; // Thông tin đơn hàng

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount * 100, // VNPay yêu cầu số tiền nhân 100
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $this->vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        // Thêm thời gian hết hạn (15 phút)
        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
        $inputData['vnp_ExpireDate'] = $expire;

        // Thêm mã ngân hàng nếu có
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        // Sắp xếp dữ liệu theo key
        ksort($inputData);
        
        // Tạo query string và hash data
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Tạo secure hash
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
        $vnp_Url = $this->vnp_Url . "?" . $query . 'vnp_SecureHash=' . $vnpSecureHash;

        return $vnp_Url;
    }

    /**
     * Xác thực chữ ký từ VNPay return
     *
     * @param Request $request
     * @return bool
     */
    public function validateSignature(Request $request): bool
    {
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        $inputData = array();

        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        return $secureHash == $vnp_SecureHash;
    }

    /**
     * Lấy dữ liệu từ VNPay return
     *
     * @param Request $request
     * @return array
     */
    public function getReturnData(Request $request): array
    {
        return [
            'vnp_TxnRef' => $request->input('vnp_TxnRef'),
            'vnp_Amount' => $request->input('vnp_Amount') / 100, // Chia 100 vì VNPay trả về số tiền nhân 100
            'vnp_ResponseCode' => $request->input('vnp_ResponseCode'),
            'vnp_TransactionNo' => $request->input('vnp_TransactionNo'),
            'vnp_BankCode' => $request->input('vnp_BankCode'),
            'vnp_PayDate' => $request->input('vnp_PayDate'),
            'vnp_OrderInfo' => $request->input('vnp_OrderInfo'),
            'vnp_TransactionStatus' => $request->input('vnp_TransactionStatus'),
            'all' => $request->all(),
        ];
    }

    /**
     * Xác thực chữ ký từ VNPay IPN
     *
     * @param array $inputData
     * @param string $vnp_SecureHash
     * @return bool
     */
    public function validateIpnSignature(array $inputData, string $vnp_SecureHash): bool
    {
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        return $secureHash == $vnp_SecureHash;
    }

    /**
     * Lấy mã ngân hàng từ payment method
     *
     * @param string $paymentMethod
     * @return string
     */
    public function getBankCode(string $paymentMethod): string
    {
        $bankCodes = [
            'vnpay_qr' => '',
            'vnpay_atm' => 'VNBANK',
            'vnpay_card' => 'INTCARD',
        ];

        return $bankCodes[$paymentMethod] ?? '';
    }
}

