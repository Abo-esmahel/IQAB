<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Enums\PaymentStatus;
use App\Models\Setting;
use App\Models\User;
use App\Services\Wallet\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WalletDepositSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        config([
            'payments.gateway_url' => '',
            'payments.gateway_secret' => '',
            'telegram.webhook_secret' => 'TODO_secret_placeholder',
            'phoneprovider.webhook_secret' => 'TODO_secret_placeholder',
        ]);
    }

    public function test_deposit_without_gateway_does_not_credit_wallet(): void
    {
        $user = User::where('email', 'demo@iqab.com')->first();
        $this->actingAs($user);

        $wallet = app(WalletService::class);
        $before = round($wallet->getBalance($user), 2);

        $this->post(route('wallet.deposit'), ['amount' => 10000])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertEquals($before, round($wallet->getBalance($user), 2), 'Balance must not change without a gateway.');
        $this->assertDatabaseMissing('payments', ['provider' => 'gateway', 'status' => PaymentStatus::Completed->value]);
    }

    public function test_gateway_callback_credits_wallet_only_after_verification(): void
    {
        config([
            'payments.gateway_url' => 'http://gateway.test',
            'payments.gateway_secret' => 'super_secret',
        ]);

        Http::fake([
            'gateway.test/payments' => Http::response([
                'data' => ['reference' => 'intent_1', 'payment_url' => 'http://gateway.test/pay/intent_1'],
            ], 200),
            'gateway.test/payments/intent_1/verify' => Http::response([
                'data' => ['status' => 'paid'],
            ], 200),
        ]);

        $user = User::where('email', 'demo@iqab.com')->first();
        $this->actingAs($user);
        $wallet = app(WalletService::class);
        $before = round($wallet->getBalance($user), 2);

        $this->post(route('wallet.deposit'), ['amount' => 500])
            ->assertRedirect('http://gateway.test/pay/intent_1');

        // Balance must NOT be credited before the gateway confirms.
        $this->assertEquals($before, round($wallet->getBalance($user), 2));

        // Gateway callback without the secret is rejected.
        $this->postJson('/api/webhooks/payments/deposit', ['reference' => 'intent_1'])
            ->assertStatus(401);

        // Gateway callback with the correct secret credits the wallet.
        $this->postJson('/api/webhooks/payments/deposit', ['reference' => 'intent_1'], [
            'X-Gateway-Secret' => 'super_secret',
        ])->assertJson(['status' => 'ok']);

        $this->assertEquals($before + 500, round($wallet->getBalance($user), 2));
        $this->assertDatabaseHas('payments', [
            'provider_reference' => 'intent_1',
            'status' => PaymentStatus::Completed->value,
        ]);
    }

    public function test_webhook_rejects_placeholder_secret(): void
    {
        $this->postJson('/api/webhooks/telegram', ['update_id' => 1])
            ->assertStatus(401);
    }

    public function test_maintenance_mode_blocks_customer_routes(): void
    {
        Setting::set('system.maintenance_mode', true);

        $user = User::where('email', 'demo@iqab.com')->first();
        $this->actingAs($user);

        $this->get('/dashboard')->assertStatus(503);

        // Admin area remains reachable.
        $admin = User::where('email', 'admin@iqab.com')->first();
        $this->actingAs($admin);
        $this->get('/admin')->assertStatus(200);
    }
}
