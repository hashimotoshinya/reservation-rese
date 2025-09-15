<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // オーナー
        $owner = User::create([
            'name' => 'テストオーナー',
            'email' => 'owner@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'owner',
        ]);
        // 管理者
        User::create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);
         // 一般ユーザー
        User::create([
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'user',
        ]);
    }
}