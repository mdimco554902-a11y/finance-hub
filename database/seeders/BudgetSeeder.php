<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Budget::count() > 0) return;

        // expanded categories to provide more sample budgets per user
        $categories = [
            ['category' => 'Groceries', 'limit' => 5000, 'color' => '#10B981'],
            ['category' => 'Dining Out', 'limit' => 3000, 'color' => '#F59E0B'],
            ['category' => 'Shopping', 'limit' => 2500, 'color' => '#EF4444'],
            ['category' => 'Entertainment', 'limit' => 1500, 'color' => '#8B5CF6'],
            ['category' => 'Transport', 'limit' => 1200, 'color' => '#3B82F6'],
            ['category' => 'Utilities', 'limit' => 4000, 'color' => '#64748B'],
            ['category' => 'Health', 'limit' => 3000, 'color' => '#F43F5E'],
            ['category' => 'Education', 'limit' => 6000, 'color' => '#8B5CF6'],
            ['category' => 'Subscriptions', 'limit' => 800, 'color' => '#06B6D4'],
            ['category' => 'Home Improvement', 'limit' => 7000, 'color' => '#F97316'],
            ['category' => 'Gifts', 'limit' => 1200, 'color' => '#F59E0B'],
            ['category' => 'Pet Care', 'limit' => 900, 'color' => '#10B981'],
        ];

        User::all()->each(function ($user) use ($categories) {
            foreach ($categories as $c) {
                Budget::updateOrCreate(
                    ['category' => $c['category'], 'user_id' => $user->id],
                    [
                        'limit_amount' => $c['limit'],
                        'color' => $c['color'],
                        'used' => rand(0, intval($c['limit'] * 0.9)),
                        'user_id' => $user->id,
                    ]
                );
            }
        });
    }
}
