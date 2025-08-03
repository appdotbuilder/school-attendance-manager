<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('marked_by')->constrained('users');
            $table->date('attendance_date')->comment('Date of the attendance record');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->comment('Attendance status');
            $table->text('notes')->nullable()->comment('Additional notes about attendance');
            $table->time('marked_at_time')->nullable()->comment('Time when attendance was marked');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('student_id');
            $table->index('class_id');
            $table->index('attendance_date');
            $table->index('status');
            $table->index(['attendance_date', 'class_id']);
            $table->index(['student_id', 'attendance_date']);
            
            // Unique constraint to prevent duplicate records
            $table->unique(['student_id', 'attendance_date'], 'unique_student_date_attendance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};