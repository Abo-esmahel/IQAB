<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_svg_upload_is_rejected(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);

        $this->actingAs($admin)->post('/admin/services', [
            'name' => 'SVG Service',
            'price' => 10,
            'image_file' => UploadedFile::fake()->create('evil.svg', 100, 'image/svg+xml'),
        ])->assertSessionHasErrors('image_file');
    }

    public function test_guest_cannot_access_admin(): void
    {
        // Guests are sent to login; authenticated non-admins get 403 (see below).
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/users')->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_admin(): void
    {
        $user = User::factory()->create(['role' => 'user', 'status' => 'active']);
        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_profile_update_cannot_escalate_role(): void
    {
        $user = User::factory()->create(['role' => 'user', 'status' => 'active']);

        $this->actingAs($user)->put('/profile', [
            'name' => 'Hacker',
            'email' => $user->email,
            'role' => 'admin',
            'status' => 'active',
        ])->assertRedirect();

        $user->refresh();
        $this->assertSame('user', $user->role);
        $this->assertSame('Hacker', $user->name);
    }

    public function test_webhook_without_secret_is_rejected(): void
    {
        $this->postJson('/api/webhooks/phone/messages', ['foo' => 'bar'])->assertUnauthorized();
        $this->postJson('/api/webhooks/telegram', ['foo' => 'bar'])->assertUnauthorized();
    }

    public function test_login_is_throttled(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/login', ['email' => 'nobody@example.com', 'password' => 'wrong']);
        }
        $response->assertStatus(429);
    }
}
