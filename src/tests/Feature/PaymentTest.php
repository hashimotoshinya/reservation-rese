<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Mockery;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_checkout_session_redirects_to_stripe_url()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $mockSession = new \stdClass();
        $mockSession->url = 'https://stripe.test/checkout-session';

        $sessionMock = Mockery::mock('alias:Stripe\Checkout\Session');
        $sessionMock->shouldReceive('create')
            ->once()
            ->andReturn($mockSession);

        $response = $this->post(route('checkout.session'));

        $response->assertRedirect($mockSession->url);
    }
}