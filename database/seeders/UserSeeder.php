<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This check prevents "Duplicate Entry" errors if you run the seeder twice
        User::updateOrCreate(
            ['email' => 'admin@test.com'], // Find the user by this email
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'), // Secure way to hash passwords
            ]
        );
    }
}