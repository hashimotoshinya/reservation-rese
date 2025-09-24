<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_favorite_a_shop()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($user)->post(route('favorites.toggle', $shop->id));

        $response->assertStatus(302);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);
    }

    public function test_authenticated_user_can_unfavorite_a_shop()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $user->favorites()->attach($shop->id);

        $response = $this->actingAs($user)->post(route('favorites.toggle', $shop->id));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);
    }

    public function test_guest_user_is_redirected_to_login_when_favoriting()
    {
        $shop = Shop::factory()->create();

        $response = $this->post(route('favorites.toggle', $shop->id));

        $response->assertRedirect(route('login'));
    }
}