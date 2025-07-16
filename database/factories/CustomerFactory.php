<?php

namespace Database\Factories;

use App\Models\CustomerCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
			'reference' => $this->faker->unique()->word,
			'started_at' => now(), // avoid random dates in base factory definitions to reduce flake in tests
			'description' => $this->faker->paragraph,
			'customer_category_id' => CustomerCategory::factory(),
        ];
    }
}
