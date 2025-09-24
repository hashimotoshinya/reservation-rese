<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_top_page_displays_shop_list()
    {
        $shops = Shop::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        foreach ($shops as $shop) {
            $response->assertSee($shop->name);
        }
    }

    public function test_shop_detail_page_displays_correctly()
    {
        $shop = Shop::factory()->create();

        $response = $this->get("/detail/{$shop->id}");

        $response->assertStatus(200)
                ->assertSee($shop->name)
                ->assertSee($shop->description);
    }

    public function test_shop_detail_page_returns_404_for_invalid_id()
    {
        $response = $this->get("/detail/99999");

        $response->assertStatus(404);
    }

    public function test_guest_user_redirected_to_login_when_toggling_favorite()
    {
        $shop = Shop::factory()->create();

        $response = $this->post("/favorites/{$shop->id}");

        $response->assertRedirect(route('login'));
    }
}