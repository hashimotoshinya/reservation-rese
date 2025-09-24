<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);
    }

    public function test_admin_dashboard_requires_authentication()
    {
        $response = $this->get('/staff/admin');
        $response->assertRedirect('/login');

        $response = $this->actingAs($this->admin)->get('/staff/admin');
        $response->assertStatus(200);
        $response->assertSee('管理者画面');
    }

    public function test_admin_can_create_owner()
    {
        $response = $this->actingAs($this->admin)->post('/admin/owners', [
            'name' => '新しいオーナー',
            'email' => 'newowner@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'newowner@example.com',
            'role' => 'owner',
        ]);
    }

    public function test_create_owner_validation_errors()
    {
        $response = $this->actingAs($this->admin)->post('/admin/owners', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors([
            'name' => '名前を入力してください。',
            'email' => '正しい形式のメールアドレスを入力してください。',
            'password' => 'パスワードは8文字以上で入力してください。',
        ]);
    }

    public function test_admin_can_send_notifications()
    {
        Notification::fake();

        $response = $this->actingAs($this->admin)->post('/admin/notifications/send', [
            'title' => 'テスト通知',
            'message' => 'これはテスト通知です。',
        ]);

        $response->assertRedirect();

    }
}