<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Transaction::count() > 0) {
            return; // avoid duplicating data on repeated seeds
        }

        $titles = [
            'Salary', 'Freelance', 'Groceries', 'Rent', 'Electricity', 'Dining Out', 'Transport', 'Gym', 'Internet', 'Coffee'
        ];

        // Create a larger, predictable set of transactions per user (default 100)
        $perUser = env('SEED_TRANSACTION_COUNT', 100);

        User::all()->each(function ($user) use ($perUser) {
            Transaction::factory()->count(intval($perUser))->state(function () use ($user) {
                return ['user_id' => $user->id];
            })->create();
        });
    }
}
