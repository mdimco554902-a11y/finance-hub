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

        // Call dedicated seeders to populate more realistic sample data
        $this->call([
            UserSeeder::class,
            BudgetSeeder::class,
            TransactionSeeder::class,
            SavingSeeder::class,
        ]);
    }
}