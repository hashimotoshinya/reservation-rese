<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $shop;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create([
            'role' => 'owner',
            'email_verified_at' => now(),
        ]);

        $this->shop = Shop::factory()->create([
            'owner_id' => $this->owner->id,
        ]);
    }

    public function test_owner_dashboard_is_displayed()
    {
        $response = $this->actingAs($this->owner)->get('/owner/dashboard');
        $response->assertStatus(200);
        $response->assertSee('店舗代表者画面');
    }

    public function test_owner_can_see_shop_create_form()
    {
        $response = $this->actingAs($this->owner)->get('/owner/shop/create');
        $response->assertStatus(200);
        $response->assertSee('新しい店舗を登録');
    }

    public function test_owner_can_register_shop()
    {
        $area = Area::factory()->create();
        $genre = Genre::factory()->create();

        $data = [
            'name' => '新規店舗',
            'description' => 'テスト説明',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'image' => null,
        ];

        $response = $this->actingAs($this->owner)->post('/owner/shop', $data);

        $response->assertRedirect('/owner/dashboard');
        $this->assertDatabaseHas('shops', ['name' => '新規店舗']);
    }

    public function test_owner_can_see_shop_edit_form()
    {
        $response = $this->actingAs($this->owner)->get("/owner/shop/{$this->shop->id}/edit");
        $response->assertStatus(200);
        $response->assertSee('店舗情報を編集');
    }

    public function test_owner_can_update_shop()
    {
        $area = Area::factory()->create();
        $genre = Genre::factory()->create();

        $data = [
            'name' => '更新店舗',
            'description' => '更新後説明',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'image' => null,
        ];

        $response = $this->actingAs($this->owner)->put("/owner/shop/{$this->shop->id}", $data);

        $response->assertRedirect('/owner/dashboard');
        $this->assertDatabaseHas('shops', ['name' => '更新店舗']);
    }

    public function test_owner_can_see_reservations_for_his_shop()
    {
        $reservation = Reservation::factory()->create([
            'shop_id' => $this->shop->id,
            'user_id' => User::factory()->create()->id,
            'date' => now()->addDay()->toDateString(),
            'time' => '18:00',
            'number' => 2,
        ]);

        $response = $this->actingAs($this->owner)->get("/owner/shop/{$this->shop->id}/reservations");

        $response->assertStatus(200);
        $response->assertSee((string) $reservation->number);
        $response->assertSee($reservation->date);
        $response->assertSee($reservation->time);
    }
}