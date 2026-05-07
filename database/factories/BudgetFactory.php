<?php

namespace Database\Factories;

use App\Models\Budget;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    public function definition(): array
    {
        $categories = ['Groceries','Dining Out','Shopping','Entertainment','Transport'];
        $category = $this->faker->unique()->randomElement($categories);
        $limit = $this->faker->randomFloat(2, 500, 10000);

        return [
            'category' => $category,
            'limit_amount' => $limit,
            'color' => $this->faker->safeHexColor(),
            'used' => $this->faker->randomFloat(2, 0, $limit * 0.9),
        ];
    }
}
