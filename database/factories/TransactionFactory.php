<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $titles = ['Salary', 'Freelance', 'Groceries', 'Rent', 'Electricity', 'Dining Out', 'Transport', 'Gym', 'Internet', 'Coffee'];
        $type = $this->faker->boolean(30) ? 'income' : 'expense';
        $amount = $type === 'income'
            ? $this->faker->randomFloat(2, 1000, 80000)
            : $this->faker->randomFloat(2, 10, 2000);

        return [
            'title' => $this->faker->randomElement($titles),
            'amount' => $amount,
            'type' => $type,
            'created_at' => $this->faker->dateTimeBetween('-180 days', 'now'),
            'updated_at' => now(),
        ];
    }
}
