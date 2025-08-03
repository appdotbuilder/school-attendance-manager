<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolClass>
 */
class SchoolClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grades = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];
        $sections = ['A', 'B', 'C'];
        
        return [
            'name' => fake()->randomElement($grades) . fake()->randomElement($sections),
            'description' => fake()->sentence(),
            'teacher_id' => User::factory()->teacher(),
            'capacity' => fake()->numberBetween(20, 35),
            'is_active' => fake()->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Indicate that the class should be active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the class should be inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}