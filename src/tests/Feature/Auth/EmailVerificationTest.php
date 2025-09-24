<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_thanks_page_is_displayed()
    {
        $response = $this->get(route('thanks'));

        $response->assertStatus(200);
        $response->assertSee('会員登録ありがとうございます');
    }

    public function test_verification_notice_is_displayed_for_unverified_user()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(200);
        $response->assertSee('登録していただいたメールアドレスに認証メールを送付しました。');
        $response->assertSee('メール認証を完了してください。');
    }

    public function test_user_can_verify_email_with_valid_link()
{
    Event::fake();

    $user = User::factory()->unverified()->create();

    $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(\Illuminate\Auth\Events\Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('thanks'));
    }

    public function test_verification_email_can_be_resent()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post(route('verification.send'));

        Notification::assertSentTo($user, VerifyEmail::class);
        $response->assertRedirect();
    }
}