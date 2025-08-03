<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

// Welcome page - shows app information
Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Protected routes (require authentication)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Student management
    Route::resource('students', StudentController::class);
    
    // Class management
    Route::resource('classes', SchoolClassController::class);
    
    // Attendance management
    Route::resource('attendance', AttendanceController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';