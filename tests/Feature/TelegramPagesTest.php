<?php

namespace Tests\Feature;

use App\Enums\TelegramServiceStatus;
use App\Enums\TelegramServiceType;
use App\Models\TelegramService;
use App\Models\TelegramServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TelegramPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user(): User
    {
        return User::factory()->create(['status' => 'active']);
    }

    protected function service(array $overrides = []): TelegramService
    {
        return TelegramService::create(array_merge([
            'name' => 'Account Lookup',
            'type' => TelegramServiceType::AccountLookup->value,
            'price' => 25.00,
            'description' => 'Look up a Telegram account.',
            'is_active' => true,
        ], $overrides));
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/telegram')->assertRedirect('/login');
    }

    public function test_index_shows_services_and_steps(): void
    {
        $this->service();

        $this->actingAs($this->user())->get('/telegram')
            ->assertOk()
            ->assertSee('Account Lookup')
            ->assertSee('Pick a service')
            ->assertSee('target_identifier');
    }

    public function test_index_shows_empty_state_without_services(): void
    {
        $this->actingAs($this->user())->get('/telegram')
            ->assertOk()
            ->assertSee('No services available');
    }

    public function test_submit_rejects_invalid_target(): void
    {
        $service = $this->service();
        $user = $this->user();

        $this->actingAs($user)->post('/telegram/submit', [
            'telegram_service_id' => $service->id,
            'target_identifier' => '!!',
        ])->assertSessionHasErrors('target_identifier');

        $this->actingAs($user)->post('/telegram/submit', [
            'telegram_service_id' => $service->id,
            'target_identifier' => 'ab',
        ])->assertSessionHasErrors('target_identifier');
    }

    public function test_submit_rejects_inactive_service(): void
    {
        $service = $this->service(['is_active' => false]);

        $this->actingAs($this->user())->post('/telegram/submit', [
            'telegram_service_id' => $service->id,
            'target_identifier' => '@someone',
        ])->assertSessionHasErrors('telegram_service_id');
    }

    public function test_result_page_renders_pretty_report_with_static_pill(): void
    {
        $user = $this->user();
        $service = $this->service();
        $req = TelegramServiceRequest::create([
            'user_id' => $user->id,
            'telegram_service_id' => $service->id,
            'target_identifier' => '@durov',
            'price' => 25.00,
            'status' => TelegramServiceStatus::Completed->value,
            'result' => ['username' => '@durov', 'account' => ['id' => 1, 'premium' => true]],
        ]);

        $content = $this->actingAs($user)->get("/telegram/result/{$req->id}")
            ->assertOk()->getContent();

        $this->assertStringContainsString('bg-emerald-500/10', $content);
        $this->assertStringContainsString('Username', $content);
        $this->assertStringContainsString('@durov', $content);
        $this->assertStringContainsString('Premium', $content);
    }

    public function test_history_shows_static_status_pill(): void
    {
        $user = $this->user();
        $service = $this->service();
        TelegramServiceRequest::create([
            'user_id' => $user->id,
            'telegram_service_id' => $service->id,
            'target_identifier' => '@x',
            'price' => 25.00,
            'status' => TelegramServiceStatus::Failed->value,
            'error_message' => 'Provider down',
        ]);

        $content = $this->actingAs($user)->get('/telegram/history')
            ->assertOk()->getContent();

        $this->assertStringContainsString('bg-red-500/10', $content);
        $this->assertStringContainsString('Failed', $content);
        $this->assertStringContainsString('@x', $content);
    }

    public function test_user_cannot_view_others_request(): void
    {
        $owner = $this->user();
        $other = User::factory()->create(['status' => 'active']);
        $service = $this->service();
        $req = TelegramServiceRequest::create([
            'user_id' => $owner->id,
            'telegram_service_id' => $service->id,
            'target_identifier' => '@durov',
            'price' => 25.00,
            'status' => TelegramServiceStatus::Processing->value,
        ]);

        $this->actingAs($other)->get("/telegram/result/{$req->id}")->assertForbidden();
    }
}
