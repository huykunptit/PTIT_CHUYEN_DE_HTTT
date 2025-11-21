<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SpeedSmsService
{
    protected string $apiUrl = 'https://api.speedsms.vn/index.php';

    public function __construct(
        protected ?string $apiKey = null,
        protected ?string $sender = null,
        protected int $smsType = 2,
        protected bool $enabled = true,
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->apiKey);
    }

    public function sendOtp(string $phone, string $message): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $normalizedPhone = $this->normalizePhone($phone);
        if (!$normalizedPhone) {
            return false;
        }

        $payload = [
            'to' => [$normalizedPhone],
            'content' => $message,
            'sms_type' => $this->smsType,
            'sender' => $this->sender,
        ];

        $response = Http::withBasicAuth($this->apiKey, 'x')
            ->acceptJson()
            ->post("{$this->apiUrl}/sms/send", $payload);

        if ($response->failed()) {
            return false;
        }

        $data = $response->json();
        return data_get($data, 'status') === 'success';
    }

    protected function normalizePhone(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (empty($digits)) {
            return null;
        }

        if (Str::startsWith($digits, '0')) {
            return '+84' . substr($digits, 1);
        }

        if (!Str::startsWith($digits, '84')) {
            return '+84' . $digits;
        }

        return '+' . $digits;
    }
}

