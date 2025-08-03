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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique()->comment('Unique student identifier');
            $table->string('first_name')->comment('Student first name');
            $table->string('last_name')->comment('Student last name');
            $table->date('date_of_birth')->comment('Student date of birth');
            $table->string('gender')->comment('Student gender');
            $table->string('parent_name')->nullable()->comment('Parent or guardian name');
            $table->string('parent_phone')->nullable()->comment('Parent contact phone');
            $table->string('parent_email')->nullable()->comment('Parent contact email');
            $table->text('address')->nullable()->comment('Student home address');
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->date('enrollment_date')->comment('Date when student enrolled');
            $table->enum('status', ['active', 'inactive', 'transferred'])->default('active')->comment('Student enrollment status');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('student_id');
            $table->index('class_id');
            $table->index('status');
            $table->index(['first_name', 'last_name']);
            $table->index(['status', 'enrollment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};