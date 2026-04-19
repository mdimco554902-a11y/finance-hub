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
        // 1. Create your Admin User (if you haven't already)
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Create the Figma-style Budgets
        Budget::updateOrCreate(
            ['category' => 'Groceries'],
            ['limit_amount' => 5000, 'color' => '#10B981'] // Green
        );

        Budget::updateOrCreate(
            ['category' => 'Dining Out'],
            ['limit_amount' => 3000, 'color' => '#F59E0B'] // Orange
        );

        Budget::updateOrCreate(
            ['category' => 'Shopping'],
            ['limit_amount' => 2500, 'color' => '#EF4444'] // Red
        );

        Budget::updateOrCreate(
            ['category' => 'Entertainment'],
            ['limit_amount' => 1500, 'color' => '#8B5CF6'] // Purple
        );
    }
}
