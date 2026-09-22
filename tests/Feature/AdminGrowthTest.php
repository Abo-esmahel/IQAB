<?php

namespace Tests\Feature;

use App\Models\ContactMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGrowthTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'status' => 'active']);
    }

    public function test_contact_page_uses_real_brand_logos(): void
    {
        ContactMethod::create([
            'name' => 'WA', 'type' => 'whatsapp', 'value' => '+1',
            'url' => 'https://wa.me/1', 'color' => '#25D366',
            'is_active' => true, 'sort_order' => 1,
        ]);

        $content = $this->get('/contact')->assertOk()->getContent();
        $this->assertStringContainsString('images/brands/whatsapp.png', $content);
        $this->assertStringContainsString('Chat Now', $content);
    }

    public function test_contact_method_image_can_be_uploaded(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post('/admin/contact-methods', [
            'name' => 'Custom Chan',
            'type' => 'other',
            'value' => '@custom',
            'image_file' => UploadedFile::fake()->image('logo.png'),
        ])->assertRedirect('/admin/contact-methods');

        $method = ContactMethod::where('name', 'Custom Chan')->firstOrFail();
        $this->assertStringStartsWith('/storage/contact/', $method->image);
    }

    public function test_admin_can_create_user_and_admin(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'secret-123',
            'password_confirmation' => 'secret-123',
            'role' => 'user',
            'status' => 'active',
        ])->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com', 'role' => 'user']);

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Second Admin',
            'email' => 'admin2@example.com',
            'password' => 'secret-123',
            'password_confirmation' => 'secret-123',
            'role' => 'admin',
            'status' => 'active',
        ])->assertRedirect('/admin/users');

        $created = User::where('email', 'admin2@example.com')->firstOrFail();
        $this->assertTrue($created->isAdmin());

        // New credentials actually work.
        $this->post('/login', ['email' => 'admin2@example.com', 'password' => 'secret-123'])
            ->assertRedirect(route('dashboard'));
    }

    public function test_admin_can_update_user_and_password(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create(['role' => 'user', 'status' => 'active']);

        $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'name' => 'Renamed',
            'email' => $user->email,
            'password' => 'brand-new-456',
            'password_confirmation' => 'brand-new-456',
            'role' => 'user',
            'status' => 'suspended',
        ])->assertRedirect("/admin/users/{$user->id}");

        $user->refresh();
        $this->assertSame('Renamed', $user->name);
        $this->assertSame('suspended', $user->status->value);

        $this->post('/logout');
        $this->post('/login', ['email' => $user->email, 'password' => 'brand-new-456'])
            ->assertSessionHasErrors('email'); // suspended: rejected
    }

    public function test_admin_cannot_demote_or_suspend_self(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put("/admin/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'user',
            'status' => 'active',
        ])->assertSessionHasErrors('role');

        $this->actingAs($admin)->put("/admin/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'admin',
            'status' => 'suspended',
        ])->assertSessionHasErrors('status');

        $this->assertTrue($admin->refresh()->isAdmin());
    }

    public function test_admin_dashboard_shows_kpis(): void
    {
        User::factory()->create(['role' => 'user', 'status' => 'active']);

        $this->actingAs($this->admin())->get('/admin')
            ->assertOk()
            ->assertSee('Total Revenue')
            ->assertSee('New Registrations')
            ->assertSee('Top Countries')
            ->assertSee('Recent Telegram Requests');
    }
}
