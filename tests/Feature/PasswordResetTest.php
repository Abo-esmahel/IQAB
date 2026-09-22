<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\QueuedResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_loads(): void
    {
        $this->get('/forgot-password')->assertOk();
    }

    public function test_reset_link_is_sent_for_existing_user(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'reset@example.com']);

        $this->post('/forgot-password', ['email' => 'reset@example.com'])
            ->assertSessionHasNoErrors();

        Notification::assertSentTo($user, QueuedResetPassword::class);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'reset2@example.com']);

        $this->post('/forgot-password', ['email' => 'reset2@example.com']);

        $token = null;
        Notification::assertSentTo($user, QueuedResetPassword::class, function ($notification) use (&$token) {
            $token = $notification->token;
            return true;
        });

        $this->assertNotNull($token);

        $this->get('/reset-password/'.$token.'?email=reset2@example.com')->assertOk();

        $this->post('/reset-password', [
            'token' => $token,
            'email' => 'reset2@example.com',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    public function test_weak_password_is_rejected_on_register(): void
    {
        $this->post('/register', [
            'name' => 'Weak User',
            'email' => 'weak@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    public function test_strong_password_is_accepted_on_register(): void
    {
        $this->post('/register', [
            'name' => 'Strong User',
            'email' => 'strong@example.com',
            'password' => 'strong-pass123',
            'password_confirmation' => 'strong-pass123',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    public function test_reset_notification_is_queued_not_sync(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Contracts\Queue\ShouldQueue::class,
            new \App\Notifications\QueuedResetPassword('tok')
        );
    }
}
