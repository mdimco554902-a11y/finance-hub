<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Budget;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create your Admin User and capture the user object
        $user = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Create the Figma-style Budgets linked to that user
        // We add 'user_id' => $user->id to ensure you own these records!
        
        Budget::updateOrCreate(
            ['category' => 'Groceries', 'user_id' => $user->id],
            ['limit_amount' => 5000, 'color' => '#10B981']
        );

        Budget::updateOrCreate(
            ['category' => 'Dining Out', 'user_id' => $user->id],
            ['limit_amount' => 3000, 'color' => '#F59E0B']
        );

        Budget::updateOrCreate(
            ['category' => 'Shopping', 'user_id' => $user->id],
            ['limit_amount' => 2500, 'color' => '#EF4444']
        );

        Budget::updateOrCreate(
            ['category' => 'Entertainment', 'user_id' => $user->id],
            ['limit_amount' => 1500, 'color' => '#8B5CF6']
        );
    }
}