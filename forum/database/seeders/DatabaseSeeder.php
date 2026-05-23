<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test user
        User::factory()
            ->count(1)
            ->create([
                'email' => 'test@example.com',
                'name' => 'Test User',
                'email_verified_at' => now(),
            ]);

        // Create additional users
        User::factory()
            ->count(4)
            ->create([
                'email_verified_at' => now(),
            ]);
    }
}
