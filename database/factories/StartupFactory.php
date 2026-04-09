<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Startup;
use Illuminate\Database\Eloquent\Factories\Factory;

use function Symfony\Component\Clock\now;

/**
 * @extends Factory<Startup>
 */
class StartupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Startup::class; 
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'activity_type' => $this->faker->randomElement(['tech', 'finance', 'ecommerce', 'health', 'education']),
            'initial_budget' => $this->faker->randomFloat(2, 1000, 50000),
            'monthly_revenue' => $this->faker->randomFloat(2, 5000, 200000),
            'monthly_expenses' => $this->faker->randomFloat(2, 3000, 150000),
            'employees_count' => $this->faker->numberBetween(1, 500),
            'user_id' => User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
