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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name of the class (e.g., Grade 5A)');
            $table->text('description')->nullable()->comment('Description of the class');
            $table->foreignId('teacher_id')->constrained('users');
            $table->integer('capacity')->default(30)->comment('Maximum number of students');
            $table->boolean('is_active')->default(true)->comment('Whether the class is currently active');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('teacher_id');
            $table->index('is_active');
            $table->index(['is_active', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};