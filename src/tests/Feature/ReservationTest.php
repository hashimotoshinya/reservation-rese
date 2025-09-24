<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shop;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_reservation()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'shop_id' => $shop->id,
            'date'    => '2025-10-01',
            'time'    => '18:00:00',
            'number'  => 2,
        ]);

        $reservation = Reservation::first();
        $response->assertRedirect(route('done', ['shop_id' => $shop->id]));
        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'date'    => '2025-10-01',
            'time'    => '18:00:00',
            'number'  => 2,
        ]);
    }

    public function test_user_can_view_edit_reservation_page()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->for($user)->create();

        $response = $this->actingAs($user)->get(route('reservations.edit', $reservation->id));

        $response->assertStatus(200);
        $response->assertSee((string) $reservation->date);
    }

    public function test_user_can_update_reservation()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->for($user)->create([
            'date' => '2025-10-01',
            'time' => '18:00:00',
            'number' => 2,
        ]);

        $response = $this->actingAs($user)->put(route('reservations.update', $reservation->id), [
            'date' => '2025-10-02',
            'time' => '19:00:00',
            'number' => 4,
        ]);

        $response->assertRedirect(route('mypage.index'));
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'date' => '2025-10-02',
            'time' => '19:00:00',
            'number' => 4,
        ]);
    }

    public function test_user_can_delete_reservation()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete(route('reservations.destroy', $reservation->id));

        $response->assertRedirect(route('mypage.index'));
        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }

    public function test_user_can_complete_reservation()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->for($user)->create([
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->patch(route('reservations.complete', $reservation->id));

        $response->assertRedirect(route('mypage.index'));
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'completed',
        ]);
    }

    public function test_validation_fails_when_date_is_missing()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'shop_id' => $shop->id,
            'time'    => '18:00:00',
            'number'  => 2,
        ]);

        $response->assertSessionHasErrors(['date' => '日付を選択してください。']);
    }

    public function test_validation_fails_when_date_is_invalid_format()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'shop_id' => $shop->id,
            'date'    => 'invalid-date',
            'time'    => '18:00:00',
            'number'  => 2,
        ]);

        $response->assertSessionHasErrors(['date' => '正しい日付を入力してください。']);
    }

    public function test_validation_fails_when_date_is_today_or_past()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $today = Carbon::today()->toDateString();

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'shop_id' => $shop->id,
            'date'    => $today,
            'time'    => '18:00:00',
            'number'  => 2,
        ]);

        $response->assertSessionHasErrors(['date' => '当日を含む過去は選択できません。']);
    }

    public function test_validation_fails_when_time_is_missing()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'shop_id' => $shop->id,
            'date'    => Carbon::tomorrow()->toDateString(),
            'number'  => 2,
        ]);

        $response->assertSessionHasErrors(['time' => '選択してください。']);
    }

    public function test_validation_fails_when_number_is_missing()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'shop_id' => $shop->id,
            'date'    => Carbon::tomorrow()->toDateString(),
            'time'    => '18:00:00',
        ]);

        $response->assertSessionHasErrors(['number' => '選択してください。']);
    }

    public function test_validation_fails_when_number_is_not_integer()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'shop_id' => $shop->id,
            'date'    => Carbon::tomorrow()->toDateString(),
            'time'    => '18:00:00',
            'number'  => 'abc',
        ]);

        $response->assertSessionHasErrors(['number' => '人数は数値で指定してください。']);
    }

    public function test_validation_fails_when_number_is_out_of_range()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'shop_id' => $shop->id,
            'date'    => Carbon::tomorrow()->toDateString(),
            'time'    => '18:00:00',
            'number'  => 0,
        ]);
        $response->assertSessionHasErrors(['number' => '人数は1〜10人の範囲で選択してください。']);

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'shop_id' => $shop->id,
            'date'    => Carbon::tomorrow()->toDateString(),
            'time'    => '18:00:00',
            'number'  => 11,
        ]);
        $response->assertSessionHasErrors(['number' => '人数は1〜10人の範囲で選択してください。']);
    }
}