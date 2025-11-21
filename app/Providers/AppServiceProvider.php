<?php

namespace App\Providers;

use App\Listeners\SePayWebhookListener;
use App\Services\Sms\SpeedSmsService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SePay\SePay\Events\SePayWebhookEvent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SpeedSmsService::class, function () {
            $config = config('services.speedsms');

            return new SpeedSmsService(
                apiKey: $config['api_key'] ?? null,
                sender: $config['sender'] ?? null,
                smsType: (int) ($config['type'] ?? 2),
                enabled: (bool) ($config['enabled'] ?? false),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Đăng ký SePay Webhook Listener
        Event::listen(
            SePayWebhookEvent::class,
            SePayWebhookListener::class,
        );
    }
}
