<?php

namespace Tests\Feature;

use App\Models\MarketService;
use App\Models\Offer;
use App\Models\PhoneNumber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'status' => 'active']);
    }

    public function test_service_image_can_be_uploaded(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post('/admin/services', [
            'name' => 'Upload Service',
            'price' => 10,
            'image_file' => UploadedFile::fake()->image('cover.jpg'),
        ]);

        $response->assertRedirect('/admin/services');
        $service = MarketService::where('name', 'Upload Service')->firstOrFail();
        $this->assertStringStartsWith('/storage/services/', $service->image);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $service->image));
    }

    public function test_service_image_url_still_works(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/services', [
            'name' => 'URL Service',
            'price' => 10,
            'image' => 'https://example.com/pic.jpg',
        ])->assertRedirect('/admin/services');

        $this->assertSame(
            'https://example.com/pic.jpg',
            MarketService::where('name', 'URL Service')->firstOrFail()->image
        );
    }

    public function test_service_image_is_replaced_and_old_deleted(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $service = MarketService::create([
            'name' => 'Old Service', 'slug' => 'old-service', 'price' => 5,
            'image' => '/storage/services/old.jpg',
        ]);
        Storage::disk('public')->put('services/old.jpg', 'old-content');

        $this->actingAs($admin)->put("/admin/services/{$service->slug}", [
            'name' => 'Old Service',
            'price' => 5,
            'image_file' => UploadedFile::fake()->image('new.jpg'),
        ])->assertRedirect('/admin/services');

        $service->refresh();
        $this->assertStringStartsWith('/storage/services/', $service->image);
        Storage::disk('public')->assertMissing('services/old.jpg');
    }

    public function test_non_image_file_is_rejected(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/services', [
            'name' => 'Bad Service',
            'price' => 10,
            'image_file' => UploadedFile::fake()->create('evil.php', 100),
        ])->assertSessionHasErrors('image_file');

        $this->assertNull(MarketService::where('name', 'Bad Service')->first());
    }

    public function test_offer_image_can_be_uploaded(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/offers', [
            'title' => 'Upload Offer',
            'offer_price' => 9.99,
            'type' => 'other',
            'image_file' => UploadedFile::fake()->image('offer.png'),
        ])->assertRedirect('/admin/offers');

        $offer = Offer::where('title', 'Upload Offer')->firstOrFail();
        $this->assertStringStartsWith('/storage/offers/', $offer->image);
    }

    public function test_number_image_can_be_uploaded(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/numbers', [
            'phone_number' => '+19998887777',
            'country' => 'United States',
            'country_code' => '+1',
            'price' => 12.5,
            'image_file' => UploadedFile::fake()->image('flag.png'),
        ])->assertRedirect('/admin/numbers');

        $number = PhoneNumber::where('phone_number', '+19998887777')->firstOrFail();
        $this->assertStringStartsWith('/storage/numbers/', $number->image);
    }

    public function test_contact_page_uses_svg_icons_not_emoji(): void
    {
        $response = $this->get('/contact');
        $response->assertOk();
        $this->assertStringNotContainsString('💬', $response->getContent());
        $this->assertStringNotContainsString('✈️', $response->getContent());
    }
}
