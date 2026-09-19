<?php

namespace App\Providers;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureUserIsActive;
use App\Models\NumberMessage;
use App\Models\NumberPurchase;
use App\Models\PhoneNumber;
use App\Models\ServicePurchase;
use App\Models\TelegramServiceRequest;
use App\Models\User;
use App\Policies\NumberMessagePolicy;
use App\Policies\NumberPurchasePolicy;
use App\Policies\PhoneNumberPolicy;
use App\Policies\ServicePurchasePolicy;
use App\Policies\TelegramServiceRequestPolicy;
use App\Policies\UserPolicy;
use App\Services\Phone\Contracts\PhoneProviderInterface;
use App\Services\Phone\PhoneProviderService;
use App\Services\Telegram\Contracts\TelegramServiceInterface;
use App\Services\Telegram\TelegramProviderService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PhoneProviderInterface::class, PhoneProviderService::class);
        $this->app->bind(TelegramServiceInterface::class, TelegramProviderService::class);
    }

    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(PhoneNumber::class, PhoneNumberPolicy::class);
        Gate::policy(NumberPurchase::class, NumberPurchasePolicy::class);
        Gate::policy(NumberMessage::class, NumberMessagePolicy::class);
        Gate::policy(TelegramServiceRequest::class, TelegramServiceRequestPolicy::class);
        Gate::policy(ServicePurchase::class, ServicePurchasePolicy::class);

        $this->autoInstallIfFresh();
    }

    /**
     * Automatically migrate and seed the database on first launch,
     * so a fresh clone works out-of-the-box without manual setup commands.
     */
    protected function autoInstallIfFresh(): void
    {
        if (app()->runningInConsole() || app()->isProduction()) {
            return;
        }

        // File cache guard: independent of the database, so it works before the DB exists.
        if (Cache::store('file')->get('iqab_auto_install_checked')) {
            return;
        }

        try {
            // Ensure the sqlite file exists before anything touches the database.
            $sqlitePath = config('database.connections.sqlite.database', null);
            if (
                config('database.default') === 'sqlite'
                && is_string($sqlitePath)
                && ! file_exists($sqlitePath)
            ) {
                touch($sqlitePath);
            }

            if (! Schema::hasTable('users')) {
                Artisan::call('migrate', ['--force' => true]);
            }

            // Re-check after migrate: only seed once, on a truly empty database.
            if (Schema::hasTable('users') && \App\Models\User::query()->doesntExist()) {
                Artisan::call('db:seed', ['--force' => true]);
            }

            Cache::store('file')->forever('iqab_auto_install_checked', true);
        } catch (\Throwable $e) {
            // Database is not reachable yet (e.g. during install); retry on next request.
            report($e);
        }
    }
}
