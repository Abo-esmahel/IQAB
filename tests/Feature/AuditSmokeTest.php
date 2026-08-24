<?php

namespace Tests\Feature;

use App\Models\PhoneNumber;
use App\Models\NumberPurchase;
use App\Models\TelegramService;
use App\Models\TelegramServiceRequest;
use App\Models\User;
use App\Enums\NumberPurchaseStatus;
use App\Enums\PhoneNumberStatus;
use App\Enums\TelegramServiceStatus;
use App\Enums\TelegramServiceType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_pages_render(): void
    {
        $user = User::factory()->create(['status' => 'active']);

        $urls = [
            '/',
            '/dashboard',
            '/marketplace',
            '/numbers',
            '/services',
            '/telegram',
            '/telegram/history',
        ];

        foreach ($urls as $url) {
            $response = $this->actingAs($user)->get($url);
            if ($response->getStatusCode() !== 200) {
                $this->fail("Status {$response->getStatusCode()} on {$url} -> " . $response->headers->get('Location'));
            }
        }
    }

    public function test_telegram_result_page_renders_for_owner(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $service = TelegramService::create([
            'name' => 'Account Lookup',
            'description' => 'Look up a Telegram account',
            'type' => TelegramServiceType::AccountLookup->value,
            'price' => 5.00,
            'is_active' => true,
        ]);

        $this->mock(\App\Services\Telegram\TelegramProviderService::class)
            ->shouldReceive('lookupAccount')
            ->andReturn(['username' => 'testuser', 'id' => 123]);

        $response = $this->actingAs($user)->post(route('telegram.submit'), [
            'telegram_service_id' => $service->id,
            'target_identifier' => '@testuser',
        ]);

        $req = TelegramServiceRequest::latest()->first();
        $response->assertRedirect(route('telegram.result', $req));

        $this->actingAs($user)->get(route('telegram.result', $req))
            ->assertStatus(200)
            ->assertSee('Completed');

        $this->assertDatabaseHas('telegram_service_requests', [
            'id' => $req->id,
            'status' => TelegramServiceStatus::Completed->value,
        ]);
    }

    public function test_admin_pages_render(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);

        $urls = [
            '/admin/users',
            '/admin/numbers',
            '/admin/services',
            '/admin/telegram/bot',
        ];

        foreach ($urls as $url) {
            $response = $this->actingAs($admin)->get($url);
            if ($response->getStatusCode() !== 200) {
                $this->fail("Status {$response->getStatusCode()} on {$url}");
            }
        }

        $user = User::factory()->create();
        $this->actingAs($admin)->get("/admin/users/{$user->id}")
            ->assertStatus(200);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_number_show_page_renders(): void
    {
        $number = PhoneNumber::factory()->create(['status' => PhoneNumberStatus::Available->value]);
        $user = User::factory()->create(['status' => 'active']);

        $this->actingAs($user)->get("/numbers/{$number->id}")
            ->assertStatus(200);
    }

    public function test_number_show_returns_404_for_non_available(): void
    {
        $number = PhoneNumber::factory()->create(['status' => PhoneNumberStatus::Disabled->value]);
        $user = User::factory()->create(['status' => 'active']);

        $this->actingAs($user)->get("/numbers/{$number->id}")
            ->assertStatus(404);
    }

    public function test_marketplace_lists_available_numbers(): void
    {
        $available = PhoneNumber::factory()->create(['status' => PhoneNumberStatus::Available->value]);
        $sold = PhoneNumber::factory()->create(['status' => PhoneNumberStatus::Expired->value]);

        $user = User::factory()->create(['status' => 'active']);
        $response = $this->actingAs($user)->get('/marketplace');

        $response->assertStatus(200);
        $response->assertSee($available->phone_number);
        $response->assertDontSee($sold->phone_number);
    }
}
