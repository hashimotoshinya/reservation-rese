<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'テストオーナー',
            'email' => 'owner@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'owner',
        ]);
    }
}