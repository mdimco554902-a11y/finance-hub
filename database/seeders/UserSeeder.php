<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This check prevents "Duplicate Entry" errors if you run the seeder twice
        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'], // Find the user by this email
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'), // Secure way to hash passwords
            ]
        );

        // Create a few sample users
        $users = User::factory()->count(5)->create();

        // Combine admin with factory users for populating transactions
        $allUsers = collect([$admin])->merge($users);

        $sampleTitles = [
            'Salary', 'Freelance', 'Groceries', 'Rent', 'Electricity', 'Dining Out', 'Transport', 'Gym', 'Internet', 'Coffee'
        ];

        foreach ($allUsers as $user) {
            // Seed a bunch of transactions for each user
            for ($i = 0; $i < 10; $i++) {
                $type = (rand(0, 100) > 60) ? 'income' : 'expense';
                $title = $sampleTitles[array_rand($sampleTitles)];
                $amount = $type === 'income' ? rand(1000, 80000) / 100 : rand(100, 20000) / 100;

                Transaction::create([
                    'user_id' => $user->id,
                    'title' => $title,
                    'amount' => $amount,
                    'type' => $type,
                    'created_at' => now()->subDays(rand(0, 90)),
                    'updated_at' => now()->subDays(rand(0, 90)),
                ]);
            }
        }
    }
}