<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => '管理者ユーザー',
            'email' => 'admin@example.com',
            'password' => Hash::make('adminpassword'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);
    }
}