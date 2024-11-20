<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'max_quota' => 100,
            'used_quota' => 0,
            'start_reg' => Date('2024-12-01'),
            'end_reg' => Date('2024-12-31'),
            'math_min_grade' => 8.00,
            'sci_min_grade' => 8.00,
        ];
    }
}
