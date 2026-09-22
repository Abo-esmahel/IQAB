<?php

namespace Tests\Feature;

use App\Enums\NumberPurchaseStatus;
use App\Enums\PhoneNumberStatus;
use App\Models\NumberPurchase;
use App\Models\PhoneNumber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyNumbersManageTest extends TestCase
{
    use RefreshDatabase;

    protected function user(): User
    {
        return User::factory()->create(['status' => 'active']);
    }

    protected function number(array $overrides = []): PhoneNumber
    {
        static $i = 0;
        $i++;

        return PhoneNumber::create(array_merge([
            'phone_number' => '+1999000' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
            'provider' => 'default',
            'country' => 'United States',
            'country_code' => '+1',
            'provider_number_id' => 'prov-' . $i,
            'status' => PhoneNumberStatus::Available->value,
            'price' => 20.00,
            'expires_at' => now()->addDays(30),
        ], $overrides));
    }

    protected function purchase(User $user, PhoneNumber $number, array $overrides = []): NumberPurchase
    {
        return NumberPurchase::create(array_merge([
            'user_id' => $user->id,
            'phone_number_id' => $number->id,
            'price' => $number->price,
            'status' => NumberPurchaseStatus::Active->value,
            'purchased_at' => now(),
            'expires_at' => now()->addDays(30),
        ], $overrides));
    }

    public function test_owner_can_update_nickname(): void
    {
        $user = $this->user();
        $purchase = $this->purchase($user, $this->number());

        $this->actingAs($user)->put("/dashboard/numbers/{$purchase->id}", ['label' => 'Business line'])
            ->assertRedirect();

        $this->assertSame('Business line', $purchase->refresh()->label);
    }

    public function test_stranger_cannot_manage_others_number(): void
    {
        $owner = $this->user();
        $stranger = User::factory()->create(['status' => 'active']);
        $purchase = $this->purchase($owner, $this->number());

        $this->actingAs($stranger)->put("/dashboard/numbers/{$purchase->id}", ['label' => 'x'])->assertForbidden();
        $this->actingAs($stranger)->post("/dashboard/numbers/{$purchase->id}/release")->assertForbidden();
        $this->actingAs($stranger)->get("/dashboard/numbers/{$purchase->id}/replace")->assertForbidden();
    }

    public function test_guest_is_redirected(): void
    {
        $user = $this->user();
        $purchase = $this->purchase($user, $this->number());

        $this->put("/dashboard/numbers/{$purchase->id}", ['label' => 'x'])->assertRedirect('/login');
    }

    public function test_release_frees_the_number(): void
    {
        $user = $this->user();
        $number = $this->number(['status' => PhoneNumberStatus::Active->value]);
        $purchase = $this->purchase($user, $number);

        $this->actingAs($user)->post("/dashboard/numbers/{$purchase->id}/release")
            ->assertRedirect(route('my-numbers.index'));

        $this->assertSame('cancelled', $purchase->refresh()->status->value);
        $this->assertSame('available', $number->refresh()->status->value);
    }

    public function test_cannot_release_expired_number(): void
    {
        $user = $this->user();
        $purchase = $this->purchase($user, $this->number(), ['status' => 'expired']);

        $this->actingAs($user)->post("/dashboard/numbers/{$purchase->id}/release")
            ->assertSessionHasNoErrors();

        $this->assertSame('expired', $purchase->refresh()->status->value);
    }

    public function test_replace_swaps_numbers(): void
    {
        $user = $this->user();
        $old = $this->number(['status' => PhoneNumberStatus::Active->value]);
        $new = $this->number(['price' => 35.00]);
        $purchase = $this->purchase($user, $old);

        $this->actingAs($user)->get("/dashboard/numbers/{$purchase->id}/replace")->assertOk();

        $this->actingAs($user)->post("/dashboard/numbers/{$purchase->id}/replace", [
            'phone_number_id' => $new->id,
        ])->assertRedirect(route('my-numbers.index'));

        $this->assertSame('cancelled', $purchase->refresh()->status->value);
        $this->assertSame('available', $old->refresh()->status->value);

        $fresh = NumberPurchase::where('user_id', $user->id)
            ->where('phone_number_id', $new->id)->firstOrFail();
        $this->assertSame('active', $fresh->status->value);
        $this->assertSame('active', $new->refresh()->status->value);
    }

    public function test_replace_rejects_unavailable_number(): void
    {
        $user = $this->user();
        $old = $this->number(['status' => PhoneNumberStatus::Active->value]);
        $taken = $this->number(['status' => PhoneNumberStatus::Active->value]);
        $purchase = $this->purchase($user, $old);

        $this->actingAs($user)->post("/dashboard/numbers/{$purchase->id}/replace", [
            'phone_number_id' => $taken->id,
        ])->assertSessionHasErrors('phone_number_id');

        $this->assertSame('active', $purchase->refresh()->status->value);
    }

    public function test_show_page_has_management_buttons(): void
    {
        $user = $this->user();
        $purchase = $this->purchase($user, $this->number());

        $content = $this->actingAs($user)->get("/dashboard/numbers/{$purchase->id}")
            ->assertOk()->getContent();

        $this->assertStringContainsString('Manage Number', $content);
        $this->assertStringContainsString('Replace Number', $content);
        $this->assertStringContainsString('Release Number', $content);
    }
}
