<?php

namespace Database\Factories;

use App\Models\Startup;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'startup_id' => Startup::factory(),
            'type' => $this->faker->randomElement(['sale', 'purchase', 'transfer']),
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'transaction_date' => now(),
            'description' => $this->faker->sentence,
            'is_suspicious' => false,
        ];
    }
}
