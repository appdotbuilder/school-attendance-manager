<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get statistics based on user role
        if ($user->role === 'administrator') {
            $stats = [
                'total_students' => Student::active()->count(),
                'total_classes' => SchoolClass::active()->count(),
                'total_teachers' => User::teachers()->count(),
                'todays_attendance' => AttendanceRecord::forDate(today())->count(),
            ];
            
            // Recent activity for administrators
            $recent_students = Student::with('schoolClass')
                ->latest()
                ->limit(5)
                ->get();
                
            $recent_classes = SchoolClass::with('teacher')
                ->latest()
                ->limit(5)
                ->get();
        } else {
            // Teacher view
            $teacher_classes = SchoolClass::where('teacher_id', $user->id)
                ->active()
                ->withCount('students')
                ->get();
                
            $stats = [
                'my_classes' => $teacher_classes->count(),
                'total_students' => $teacher_classes->sum('students_count'),
                'todays_attendance' => AttendanceRecord::whereIn('class_id', $teacher_classes->pluck('id'))
                    ->forDate(today())
                    ->count(),
                'present_today' => AttendanceRecord::whereIn('class_id', $teacher_classes->pluck('id'))
                    ->forDate(today())
                    ->present()
                    ->count(),
            ];
            
            $recent_students = null;
            $recent_classes = $teacher_classes;
        }
        
        return Inertia::render('dashboard', [
            'stats' => $stats,
            'user_role' => $user->role,
            'recent_students' => $recent_students,
            'recent_classes' => $recent_classes,
        ]);
    }
}