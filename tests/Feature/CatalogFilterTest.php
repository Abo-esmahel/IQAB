<?php

namespace Tests\Feature;

use App\Enums\PhoneNumberStatus;
use App\Models\MarketService;
use App\Models\PhoneNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function seedCatalog(): void
    {
        PhoneNumber::create([
            'phone_number' => '+10000000001', 'provider' => 'default',
            'country' => 'United States', 'country_code' => '+1',
            'provider_number_id' => 'p1', 'status' => PhoneNumberStatus::Available->value,
            'price' => 30.00, 'expires_at' => now()->addDays(10),
        ]);
        PhoneNumber::create([
            'phone_number' => '+10000000002', 'provider' => 'default',
            'country' => 'Germany', 'country_code' => '+49',
            'provider_number_id' => 'p2', 'status' => PhoneNumberStatus::Available->value,
            'price' => 10.00, 'expires_at' => now()->addDays(10),
        ]);
        MarketService::create([
            'name' => 'Alpha Check', 'slug' => 'alpha-check', 'price' => 5.00,
            'is_active' => true, 'category' => 'Verification',
        ]);
    }

    public function test_marketplace_loads_with_valid_filters(): void
    {
        $this->seedCatalog();

        $this->get('/marketplace?tab=numbers&country=Germany&min_price=5&max_price=20&sort=price_asc')
            ->assertOk()
            ->assertSee('+10000000002')
            ->assertDontSee('+10000000001');
    }

    public function test_invalid_tab_is_rejected(): void
    {
        $this->get('/marketplace?tab=drop-table')->assertRedirect()->assertSessionHasErrors('tab');
    }

    public function test_non_numeric_price_is_rejected(): void
    {
        $this->get('/marketplace?min_price=abc')->assertRedirect()->assertSessionHasErrors('min_price');
        $this->get('/numbers?max_price=much')->assertRedirect()->assertSessionHasErrors('max_price');
    }

    public function test_negative_price_is_rejected(): void
    {
        $this->get('/marketplace?min_price=-5')->assertRedirect()->assertSessionHasErrors('min_price');
    }

    public function test_inverted_price_range_is_auto_fixed(): void
    {
        $this->seedCatalog();

        // min > max would match nothing; the filter swaps them instead.
        $this->get('/marketplace?tab=numbers&min_price=50&max_price=5')
            ->assertOk()
            ->assertSee('+10000000001')
            ->assertSee('+10000000002');
    }

    public function test_sort_price_ascending_orders_numbers(): void
    {
        $this->seedCatalog();

        $content = $this->get('/numbers?sort=price_asc')->assertOk()->getContent();
        $cheapPos = strpos($content, '+10000000002');
        $expensivePos = strpos($content, '+10000000001');
        $this->assertNotFalse($cheapPos);
        $this->assertNotFalse($expensivePos);
        $this->assertLessThan($expensivePos, $cheapPos, 'Cheaper number should render first.');
    }

    public function test_invalid_sort_is_rejected(): void
    {
        $user = \App\Models\User::factory()->create(['status' => 'active']);

        $this->actingAs($user)->get('/services?sort=popular')
            ->assertRedirect()->assertSessionHasErrors('sort');
    }

    public function test_search_wildcards_are_escaped(): void
    {
        $this->seedCatalog();

        // Literal % should not match everything or crash.
        $this->get('/marketplace?search=%25')->assertOk();

        $user = \App\Models\User::factory()->create(['status' => 'active']);
        $this->actingAs($user)->get('/services?search=' . urlencode('100%'))->assertOk();
    }

    public function test_overlong_search_is_rejected(): void
    {
        $this->get('/marketplace?search=' . str_repeat('a', 101))
            ->assertRedirect()->assertSessionHasErrors('search');
    }
}
