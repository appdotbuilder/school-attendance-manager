<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => 'STU' . fake()->unique()->numberBetween(10000, 99999),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'date_of_birth' => fake()->dateTimeBetween('-12 years', '-5 years')->format('Y-m-d'),
            'gender' => fake()->randomElement(['male', 'female']),
            'parent_name' => fake()->name(),
            'parent_phone' => fake()->phoneNumber(),
            'parent_email' => fake()->safeEmail(),
            'address' => fake()->address(),
            'class_id' => SchoolClass::factory(),
            'enrollment_date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'status' => fake()->randomElement(['active', 'inactive', 'transferred']),
        ];
    }

    /**
     * Indicate that the student should be active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the student should be inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the student should be transferred.
     */
    public function transferred(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'transferred',
        ]);
    }
}