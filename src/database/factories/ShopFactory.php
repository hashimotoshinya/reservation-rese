<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Area;
use App\Models\Genre;
use App\Models\User;

class ShopFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => $this->faker->company,
            'description' => $this->faker->realText(100),
            'area_id'     => Area::factory(),
            'genre_id'    => Genre::factory(),
            'image_path'  => $this->faker->imageUrl(640, 480, 'food', true),
            'owner_id'    => User::factory(),
        ];
    }
}