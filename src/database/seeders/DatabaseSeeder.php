<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            OwnerSeeder::class,
            UserSeeder::class,
            AreaSeeder::class,
            GenreSeeder::class,
            ShopSeeder::class,
            ReservationSeeder::class,
        ]);
    }
}
