<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginLogoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\UserSeeder::class);
    }

    public function test_user_can_login_and_logout_with_fortify()
    {
        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');

        $response = $this->post('/logout');
        $this->assertGuest();
        $response->assertRedirect('/menu2');
    }

    public function test_staff_admin_can_login_and_redirect_to_admin_dashboard()
    {
        $response = $this->post(route('staff.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/staff/admin');
    }

    public function test_staff_owner_can_login_and_redirect_to_owner_dashboard()
    {
        $response = $this->post(route('staff.login.submit'), [
            'email' => 'owner@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('staff/owner');
    }

    public function test_normal_user_cannot_login_via_staff_login()
    {
        $response = $this->from(route('staff.login'))
            ->post(route('staff.login.submit'), [
                'email' => 'user@example.com',
                'password' => 'password123',
            ]);

        $this->assertGuest();
        $response->assertRedirect(route('staff.login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_staff_login_fails_with_invalid_credentials()
    {
        $response = $this->from(route('staff.login'))
            ->post(route('staff.login.submit'), [
                'email' => 'admin@example.com',
                'password' => 'wrongpassword',
            ]);

        $this->assertGuest();
        $response->assertRedirect(route('staff.login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_staff_can_logout_and_redirect_to_login_page()
    {
        $this->post(route('staff.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();

        $response = $this->post(route('staff.logout'));

        $this->assertGuest();
        $response->assertRedirect(route('staff.login'));
    }
}