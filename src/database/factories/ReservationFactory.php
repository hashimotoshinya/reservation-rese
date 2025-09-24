<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'shop_id' => Shop::factory(),
            'date' => $this->faker->date(),
            'time' => $this->faker->time(),
            'number' => $this->faker->numberBetween(1, 5),
            'status' => 'active',
            'qr_token' => $this->faker->uuid,
        ];
    }
}