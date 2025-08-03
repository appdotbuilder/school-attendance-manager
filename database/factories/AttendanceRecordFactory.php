<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceRecord>
 */
class AttendanceRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'class_id' => SchoolClass::factory(),
            'marked_by' => User::factory()->teacher(),
            'attendance_date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'status' => fake()->randomElement(['present', 'absent', 'late', 'excused']),
            'notes' => fake()->optional(0.3)->sentence(), // 30% chance of having notes
            'marked_at_time' => fake()->time('H:i'),
        ];
    }

    /**
     * Indicate that the attendance should be present.
     */
    public function present(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'present',
        ]);
    }

    /**
     * Indicate that the attendance should be absent.
     */
    public function absent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'absent',
        ]);
    }

    /**
     * Indicate that the attendance should be late.
     */
    public function late(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'late',
        ]);
    }

    /**
     * Indicate that the attendance should be excused.
     */
    public function excused(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'excused',
        ]);
    }
}