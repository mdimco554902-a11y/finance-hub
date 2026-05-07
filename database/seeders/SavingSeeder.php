<?php

namespace Database\Seeders;

use App\Models\Saving;
use App\Models\User;
use Illuminate\Database\Seeder;

class SavingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Saving::count() > 0) return;

        // larger and more varied savings goals per user
        $goals = [
            ['title' => 'Emergency Fund', 'target' => 50000, 'color' => '#10B981'],
            ['title' => 'New Laptop', 'target' => 80000, 'color' => '#2563EB'],
            ['title' => 'Vacation', 'target' => 60000, 'color' => '#F59E0B'],
            ['title' => 'Car Downpayment', 'target' => 150000, 'color' => '#EF4444'],
            ['title' => 'Wedding', 'target' => 200000, 'color' => '#8B5CF6'],
            ['title' => 'Home Renovation', 'target' => 100000, 'color' => '#3B82F6'],
            ['title' => 'Gadget Fund', 'target' => 30000, 'color' => '#06B6D4'],
        ];

        User::all()->each(function ($user) use ($goals) {
            foreach ($goals as $g) {
                Saving::updateOrCreate([
                    'user_id' => $user->id,
                    'title' => $g['title'],
                ], [
                    'target_amount' => $g['target'],
                    'current_amount' => rand(0, intval($g['target'] * 0.6)),
                    'color' => $g['color'],
                    'user_id' => $user->id,
                ]);
            }

            // also create a few random extra goals to increase dataset
            for ($i = 0; $i < 3; $i++) {
                $colors = ['#10B981','#2563EB','#F59E0B','#EF4444'];
                Saving::create([
                    'user_id' => $user->id,
                    'title' => 'Goal ' . ucfirst(uniqid()),
                    'target_amount' => rand(5000, 100000),
                    'current_amount' => rand(0, 30000),
                    'color' => $colors[array_rand($colors)],
                ]);
            }
        });
    }
}
