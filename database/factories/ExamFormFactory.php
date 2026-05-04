<?php

namespace Database\Factories;

use App\Models\examform;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<examform>
 */
class ExamFormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => fake()->sentence(),
            "status" => fake()->randomElement(['active', 'disabled']),
            "exam_type" => fake()->randomElement(['single_choice', 'multi_choice', 'direct_questions']),
            "duration" => fake()->randomNumber(1, 5),
            "category_id" => null,
            "user_id" => User::factory()
        ];
    }
}
