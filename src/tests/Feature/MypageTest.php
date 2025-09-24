<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class MypageTest extends TestCase
{
    use RefreshDatabase;

    public function test_mypage_displays_reservations_and_favorites()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'date'    => Carbon::tomorrow()->toDateString(),
            'time'    => '18:00:00',
            'number'  => 2,
        ]);

        $user->favorites()->attach($shop->id);

        $response = $this->actingAs($user)->get(route('mypage.index'));

        $response->assertStatus(200);
        $response->assertSee($shop->name);
        $response->assertSee((string) $reservation->number);
    }

    public function test_mypage_displays_qr_code_when_qr_token_exists()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $reservation = Reservation::factory()->create([
            'user_id'   => $user->id,
            'shop_id'   => $shop->id,
            'qr_token'  => 'dummy-token-1234',
            'date'      => now()->addDay()->toDateString(),
            'time'      => '19:00:00',
            'number'    => 3,
        ]);

        $response = $this->actingAs($user)->get(route('mypage.index'));

        $response->assertStatus(200);
        $response->assertSee('<svg', false);
    }

    public function test_review_create_page_displays_correctly()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('reviews.create', $reservation->id));

        $response->assertStatus(200);
        $response->assertSee('レビュー');
    }

    public function test_user_can_submit_review()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
        ]);
        $shop = $reservation->shop;

        $response = $this->actingAs($user)->post(route('reviews.store', $reservation->id), [
            'rating'  => 5,
            'comment' => '最高のお店でした！',
        ]);

        $response->assertRedirect(route('mypage.index'));
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'rating'  => 5,
            'comment' => '最高のお店でした！',
        ]);
    }

    public function test_review_validation_fails_with_invalid_rating()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('reviews.store', $reservation->id), [
            'rating'  => 10,
            'comment' => 'テストコメント',
        ]);

        $response->assertSessionHasErrors(['rating']);
    }
}