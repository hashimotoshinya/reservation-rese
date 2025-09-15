<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Shop;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'user@example.com')->first();
        $shop = Shop::first();

        if (!$user || !$shop) {
            $this->command->warn('User or Shop not found. Please seed them first.');
            return;
        }

        Reservation::create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'date'    => now()->toDateString(),
            'time'    => '12:00:00',
            'number'  => 2,
            'status'  => 'active',
        ]);
    }
}