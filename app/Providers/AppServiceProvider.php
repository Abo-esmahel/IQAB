<?php

namespace App\Providers;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureUserIsActive;
use App\Models\NumberMessage;
use App\Models\NumberPurchase;
use App\Models\Payment;
use App\Models\PhoneNumber;
use App\Models\ServicePurchase;
use App\Models\TelegramServiceRequest;
use App\Models\User;
use App\Policies\NumberMessagePolicy;
use App\Policies\NumberPurchasePolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PhoneNumberPolicy;
use App\Policies\ServicePurchasePolicy;
use App\Policies\TelegramServiceRequestPolicy;
use App\Policies\UserPolicy;
use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\PaymentGatewayService;
use App\Services\Phone\Contracts\PhoneProviderInterface;
use App\Services\Phone\PhoneProviderService;
use App\Services\Telegram\Contracts\TelegramServiceInterface;
use App\Services\Telegram\TelegramProviderService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PhoneProviderInterface::class, PhoneProviderService::class);
        $this->app->bind(TelegramServiceInterface::class, TelegramProviderService::class);
        $this->app->bind(PaymentGatewayInterface::class, PaymentGatewayService::class);
    }

    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(PhoneNumber::class, PhoneNumberPolicy::class);
        Gate::policy(NumberPurchase::class, NumberPurchasePolicy::class);
        Gate::policy(NumberMessage::class, NumberMessagePolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(TelegramServiceRequest::class, TelegramServiceRequestPolicy::class);
        Gate::policy(ServicePurchase::class, ServicePurchasePolicy::class);
    }
}
