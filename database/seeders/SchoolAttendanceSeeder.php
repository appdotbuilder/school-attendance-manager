<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SchoolAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default administrator
        $admin = User::create([
            'name' => 'School Administrator',
            'email' => 'admin@school.test',
            'password' => Hash::make('password'),
            'role' => 'administrator',
            'email_verified_at' => now(),
        ]);

        // Create sample teachers
        $teachers = User::factory()
            ->teacher()
            ->count(5)
            ->create();

        // Create sample classes with specific teachers
        $classes = collect();
        
        $classNames = [
            'Grade 1A' => 'Elementary Mathematics and Reading',
            'Grade 2B' => 'Elementary Science and Arts',
            'Grade 3A' => 'Intermediate Mathematics',
            'Grade 4B' => 'Intermediate Science',
            'Grade 5A' => 'Advanced Elementary Studies',
        ];

        foreach ($classNames as $name => $description) {
            $classes->push(SchoolClass::create([
                'name' => $name,
                'description' => $description,
                'teacher_id' => $teachers->random()->id,
                'capacity' => random_int(25, 35),
                'is_active' => true,
            ]));
        }

        // Create students for each class
        foreach ($classes as $class) {
            $studentCount = random_int(20, $class->capacity);
            
            for ($i = 1; $i <= $studentCount; $i++) {
                Student::create([
                    'student_id' => 'STU' . str_pad((string)($class->id * 1000 + $i), 5, '0', STR_PAD_LEFT),
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'date_of_birth' => fake()->dateTimeBetween('-12 years', '-5 years')->format('Y-m-d'),
                    'gender' => fake()->randomElement(['male', 'female']),
                    'parent_name' => fake()->name(),
                    'parent_phone' => fake()->phoneNumber(),
                    'parent_email' => fake()->safeEmail(),
                    'address' => fake()->address(),
                    'class_id' => $class->id,
                    'enrollment_date' => fake()->dateTimeBetween('-1 year', '-1 month')->format('Y-m-d'),
                    'status' => 'active',
                ]);
            }
        }

        // Create attendance records for the last 30 days
        $students = Student::with('schoolClass')->get();
        $startDate = now()->subDays(30);
        
        foreach ($students as $student) {
            for ($day = 0; $day < 30; $day++) {
                $attendanceDate = $startDate->copy()->addDays($day);
                
                // Skip weekends
                if ($attendanceDate->isWeekend()) {
                    continue;
                }
                
                // 85% chance of being present
                $status = fake()->randomElement([
                    'present', 'present', 'present', 'present', 'present',
                    'present', 'present', 'present', 'absent', 'late'
                ]);
                
                AttendanceRecord::create([
                    'student_id' => $student->id,
                    'class_id' => $student->class_id,
                    'marked_by' => $student->schoolClass->teacher_id,
                    'attendance_date' => $attendanceDate->format('Y-m-d'),
                    'status' => $status,
                    'notes' => $status !== 'present' ? fake()->optional(0.5)->sentence() : null,
                    'marked_at_time' => fake()->time('H:i'),
                ]);
            }
        }
    }
}