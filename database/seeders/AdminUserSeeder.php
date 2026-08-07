<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@mediaflow.app'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        if (app()->environment('local', 'testing')) {
            User::factory()->create([
                'email' => 'user@mediaflow.app',
                'password' => Hash::make('user123'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
