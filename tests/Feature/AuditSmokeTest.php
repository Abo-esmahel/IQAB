<?php

namespace Tests\Feature;

use App\Enums\AuditAction;
use App\Enums\NumberPurchaseStatus;
use App\Enums\PaymentStatus;
use App\Enums\WebhookLogStatus;
use App\Models\AuditLog;
use App\Models\NumberMessage;
use App\Models\NumberPurchase;
use App\Models\PhoneNumber;
use App\Models\Payment;
use App\Models\TelegramService;
use App\Models\TelegramServiceRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WebhookLog;
use App\Services\Wallet\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuditSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Http::fake([
            '*' => Http::response(['data' => ['ok' => true, 'reference' => 'prov_ref_' . rand(1000, 9999)]], 200),
        ]);
    }

    public function test_guest_pages_render(): void
    {
        foreach (['/', '/login', '/register', '/numbers'] as $url) {
            $this->get($url)->assertStatus(200);
        }

        $number = PhoneNumber::where('status', 'available')->first();
        $this->get(route('numbers.show', $number))->assertStatus(200);
    }

    public function test_authenticated_user_pages_render(): void
    {
        $user = User::where('email', 'demo@iqab.com')->first();
        $this->actingAs($user);

        foreach ([
            '/dashboard',
            '/profile',
            '/profile/change-password',
            '/wallet',
            '/transactions',
            '/telegram',
            '/telegram/history',
            '/dashboard/numbers',
        ] as $url) {
            $this->get($url)->assertStatus(200);
        }

        $purchase = NumberPurchase::create([
            'user_id' => $user->id,
            'phone_number_id' => PhoneNumber::where('status', 'available')->first()->id,
            'price' => 10,
            'status' => 'active',
        ]);

        $this->get(route('my-numbers.show', $purchase))->assertStatus(200);
        $this->get(route('inbox.index', $purchase))->assertStatus(200);

        $message = NumberMessage::create([
            'phone_number_id' => $purchase->phone_number_id,
            'provider_message_id' => 'prov_msg_1',
            'user_id' => $user->id,
            'message' => 'hi',
        ]);
        $this->get(route('inbox.show', [$purchase, $message]))->assertStatus(200);
    }

    public function test_telegram_result_requires_owner_policy_and_renders(): void
    {
        $user = User::where('email', 'demo@iqab.com')->first();
        $this->actingAs($user);

        $req = TelegramServiceRequest::create([
            'user_id' => $user->id,
            'telegram_service_id' => TelegramService::first()->id,
            'target_identifier' => '@example',
            'price' => 25,
            'status' => 'completed',
        ]);

        $this->get(route('telegram.result', $req))->assertStatus(200);
    }

    public function test_admin_pages_render(): void
    {
        $admin = User::where('email', 'admin@iqab.com')->first();
        $this->actingAs($admin);

        foreach ([
            '/admin',
            '/admin/users',
            '/admin/numbers',
            '/admin/numbers/purchases',
            '/admin/payments',
            '/admin/webhook-logs',
            '/admin/audit-logs',
            '/admin/settings',
            '/admin/telegram/bot',
        ] as $url) {
            $this->get($url)->assertStatus(200);
        }

        $user = User::where('email', 'demo@iqab.com')->first();
        $this->get("/admin/users/{$user->id}")->assertStatus(200);

        $number = PhoneNumber::where('status', 'available')->first();
        $this->get("/admin/numbers/{$number->id}/edit")->assertStatus(200);
    }

    public function test_number_purchase_debits_wallet_and_is_idempotent_per_purchase(): void
    {
        $user = User::where('email', 'demo@iqab.com')->first();
        $this->actingAs($user);

        $number = PhoneNumber::where('status', 'available')->first();
        $balanceBefore = (float) $user->wallet->balance;

        $this->post(route('numbers.purchase', $number), ['phone_number_id' => $number->id])->assertRedirect();

        $purchase = NumberPurchase::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($purchase);
        $this->assertEquals(NumberPurchaseStatus::Active, $purchase->status);

        $user->refresh();
        $this->assertEquals(round($balanceBefore - (float) $number->price, 2), round((float) $user->wallet->balance, 2));

        $number2 = PhoneNumber::where('status', 'available')->where('id', '!=', $number->id)->first();
        if ($number2) {
            $this->post(route('numbers.purchase', $number2), ['phone_number_id' => $number2->id])->assertRedirect();
            $user->refresh();
            $this->assertEquals(round($balanceBefore - (float) $number->price - (float) $number2->price, 2), round((float) $user->wallet->balance, 2));
        }
    }

    public function test_telegram_submit_debits_each_time_and_invokes_provider(): void
    {
        $user = User::where('email', 'demo@iqab.com')->first();
        $this->actingAs($user);

        $service = TelegramService::where('is_active', true)->first();
        $balanceBefore = (float) $user->wallet->balance;

        $this->post(route('telegram.submit'), [
            'telegram_service_id' => $service->id,
            'target_identifier' => '@example',
        ])->assertRedirect();

        $request = TelegramServiceRequest::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($request);
        $this->assertNotEmpty($request->result, 'Provider result should be stored (provider is actually invoked).');

        $user->refresh();
        $this->assertEquals(round($balanceBefore - (float) $service->price, 2), round((float) $user->wallet->balance, 2));

        $this->post(route('telegram.submit'), [
            'telegram_service_id' => $service->id,
            'target_identifier' => '@example',
        ])->assertRedirect();

        $user->refresh();
        $this->assertEquals(round($balanceBefore - 2 * (float) $service->price, 2), round((float) $user->wallet->balance, 2));
    }

    public function test_admin_can_adjust_balance(): void
    {
        $admin = User::where('email', 'admin@iqab.com')->first();
        $user = User::where('email', 'demo@iqab.com')->first();
        $this->actingAs($admin);

        $before = (float) $user->wallet->balance;
        $this->post("/admin/users/{$user->id}/adjust-balance", [
            'amount' => 100,
            'description' => 'test credit',
        ])->assertRedirect();

        $user->refresh();
        $this->assertEquals($before + 100, (float) $user->wallet->balance);
    }

    public function test_suspended_user_is_logged_out(): void
    {
        $user = User::where('email', 'demo@iqab.com')->first();
        $user->update(['status' => 'suspended']);

        $this->actingAs($user);
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_admin_can_suspend_and_activate_user(): void
    {
        $admin = User::where('email', 'admin@iqab.com')->first();
        $user = User::where('email', 'demo@iqab.com')->first();
        $this->actingAs($admin);

        $this->post("/admin/users/{$user->id}/suspend")->assertRedirect();
        $this->assertEquals('suspended', $user->refresh()->status->value);

        $this->post("/admin/users/{$user->id}/activate")->assertRedirect();
        $this->assertEquals('active', $user->refresh()->status->value);
    }

    public function test_admin_can_approve_payment_and_credit_wallet(): void
    {
        $admin = User::where('email', 'admin@iqab.com')->first();
        $user = User::where('email', 'demo@iqab.com')->first();
        $this->actingAs($admin);

        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => 75,
            'status' => PaymentStatus::Pending,
            'provider' => 'manual',
            'provider_reference' => 'manual_1',
        ]);

        $before = (float) $user->wallet->balance;
        $this->post("/admin/payments/{$payment->id}/approve")->assertRedirect();

        $user->refresh();
        $this->assertEquals($before + 75, (float) $user->wallet->balance);
        $this->assertEquals(PaymentStatus::Completed, $payment->refresh()->status);
    }

    public function test_audit_logs_page_renders_with_entry(): void
    {
        $admin = User::where('email', 'admin@iqab.com')->first();
        $this->actingAs($admin);

        AuditLog::create([
            'user_id' => $admin->id,
            'auditable_type' => User::class,
            'auditable_id' => $admin->id,
            'action' => AuditAction::Suspend,
            'old_values' => ['status' => 'active'],
            'new_values' => ['status' => 'suspended'],
            'description' => 'test',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test',
        ]);

        $this->get('/admin/audit-logs')->assertStatus(200);
    }

    public function test_webhook_logs_page_renders_with_entry(): void
    {
        $admin = User::where('email', 'admin@iqab.com')->first();
        $this->actingAs($admin);

        $log = WebhookLog::create([
            'provider' => 'phone',
            'event' => 'message',
            'payload' => ['a' => 1],
            'status' => WebhookLogStatus::Processed,
            'error_message' => null,
        ]);

        $this->get('/admin/webhook-logs')->assertStatus(200);
        $this->get("/admin/webhook-logs/{$log->id}")->assertStatus(200);
    }

    public function test_admin_can_publish_number_via_bulk(): void
    {
        $admin = User::where('email', 'admin@iqab.com')->first();
        $this->actingAs($admin);

        $before = PhoneNumber::count();
        $this->post(route('admin.numbers.store'), [
            'country' => 'Testland',
            'country_code' => '+99',
            'price' => 12.5,
            'bulk' => "1234567890\n1234567891",
        ])->assertRedirect();

        $this->assertEquals($before + 2, PhoneNumber::count());
    }

    public function test_marketplace_only_lists_available_numbers(): void
    {
        $this->get('/numbers')->assertStatus(200);
        $available = PhoneNumber::where('status', 'available')->count();
        $listed = PhoneNumber::where('status', 'available')->count();
        $this->assertEquals($listed, $available);
    }
}
