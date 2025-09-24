<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

        $this->assertAuthenticated();

        Notification::assertSentTo(User::first(), VerifyEmail::class);
    }

    public function test_user_is_logged_out_after_email_verification_and_redirected_to_thanks()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create([
            'email' => 'verify@example.com',
            'password' => Hash::make('password123'),
        ]);

        $verificationUrl = \URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect(route('thanks'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        $this->assertGuest();

        $this->get(route('thanks'))->assertSee('登録ありがとうございます');
    }

    public function test_name_is_required()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_name_must_not_exceed_255_characters()
    {
        $response = $this->post('/register', [
            'name' => str_repeat('a', 256),
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_email_must_be_unique()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_password_must_be_confirmed()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }
}