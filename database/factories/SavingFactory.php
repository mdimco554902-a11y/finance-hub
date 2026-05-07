<?php

namespace Database\Factories;

use App\Models\Saving;
use Illuminate\Database\Eloquent\Factories\Factory;

class SavingFactory extends Factory
{
    protected $model = Saving::class;

    public function definition(): array
    {
        $title = $this->faker->randomElement(['Emergency Fund','New Laptop','Vacation','Car Downpayment']);
        $target = $this->faker->randomFloat(2, 10000, 200000);
        $current = $this->faker->randomFloat(2, 0, $target * 0.6);

        return [
            'title' => $title,
            'target_amount' => $target,
            'current_amount' => $current,
            'color' => $this->faker->safeHexColor(),
        ];
    }
}
