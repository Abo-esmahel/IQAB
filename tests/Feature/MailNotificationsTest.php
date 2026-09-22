<?php

namespace Tests\Feature;

use App\Enums\NumberPurchaseStatus;
use App\Enums\PhoneNumberStatus;
use App\Enums\TelegramServiceStatus;
use App\Enums\TelegramServiceType;
use App\Mail\NumberAssignedMail;
use App\Mail\TelegramRequestStatusMail;
use App\Mail\WelcomeMail;
use App\Models\NumberPurchase;
use App\Models\PhoneNumber;
use App\Models\TelegramService;
use App\Models\TelegramServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_queues_welcome_email(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'Mail User',
            'email' => 'mailuser@example.com',
            'password' => 'welcome-123',
            'password_confirmation' => 'welcome-123',
        ])->assertRedirect('/dashboard');

        Mail::assertQueued(WelcomeMail::class, fn ($mail) => $mail->hasTo('mailuser@example.com'));
    }

    public function test_admin_assign_queues_number_email(): void
    {
        Mail::fake();
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $user = User::factory()->create(['role' => 'user', 'status' => 'active']);
        $number = PhoneNumber::create([
            'phone_number' => '+12223334444', 'provider' => 'default',
            'country' => 'United States', 'country_code' => '+1',
            'provider_number_id' => 'mail-1', 'status' => PhoneNumberStatus::Available->value,
            'price' => 10, 'expires_at' => now()->addDays(30),
        ]);

        $this->actingAs($admin)->post("/admin/users/{$user->id}/assign-number", [
            'phone_number_id' => $number->id,
        ])->assertRedirect();

        Mail::assertQueued(NumberAssignedMail::class, fn ($mail) => $mail->hasTo($user->email));
    }

    public function test_welcome_mail_renders_with_password(): void
    {
        $user = User::factory()->make(['name' => 'Test', 'email' => 't@example.com']);
        $mailable = new WelcomeMail($user, 'temp-1234');

        $html = $mailable->render();
        $this->assertStringContainsString('Welcome to', $html);
        $this->assertStringContainsString('temp-1234', $html);
        $this->assertSame('Welcome to IQAB — your account is ready', $mailable->envelope()->subject);
    }

    public function test_telegram_status_mail_subjects(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $service = TelegramService::create([
            'name' => 'Lookup', 'type' => TelegramServiceType::AccountLookup->value,
            'price' => 5, 'is_active' => true,
        ]);

        $ok = TelegramServiceRequest::create([
            'user_id' => $user->id, 'telegram_service_id' => $service->id,
            'target_identifier' => '@u', 'price' => 5,
            'status' => TelegramServiceStatus::Completed->value,
            'result' => ['username' => '@u'],
        ]);
        $fail = TelegramServiceRequest::create([
            'user_id' => $user->id, 'telegram_service_id' => $service->id,
            'target_identifier' => '@v', 'price' => 5,
            'status' => TelegramServiceStatus::Failed->value,
            'error_message' => 'boom',
        ]);

        $this->assertStringContainsString('ready', (new TelegramRequestStatusMail($user, $ok))->envelope()->subject);
        $this->assertStringContainsString('attention', (new TelegramRequestStatusMail($user, $fail))->envelope()->subject);
        $this->assertStringContainsString('boom', (new TelegramRequestStatusMail($user, $fail))->render());
    }

    public function test_expiring_notification_has_mail_channel(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $number = PhoneNumber::create([
            'phone_number' => '+19991112222', 'provider' => 'default',
            'country' => 'United States', 'country_code' => '+1',
            'provider_number_id' => 'mail-exp-1', 'status' => PhoneNumberStatus::Available->value,
            'price' => 5, 'expires_at' => now()->addDays(30),
        ]);
        $purchase = NumberPurchase::create([
            'user_id' => $user->id, 'phone_number_id' => $number->id, 'price' => 1,
            'status' => NumberPurchaseStatus::Active->value,
        ]);

        $notification = new \App\Notifications\NumberExpiringNotification($purchase, '5 hours');
        $this->assertContains('mail', $notification->via($user));
        $this->assertContains('database', $notification->via($user));
    }
}
