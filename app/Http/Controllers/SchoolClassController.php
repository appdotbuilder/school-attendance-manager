<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolClassRequest;
use App\Http\Requests\UpdateSchoolClassRequest;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the classes.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = SchoolClass::with(['teacher', 'students']);
        
        // Teachers can only see their own classes
        if ($user->role === 'teacher') {
            $query->where('teacher_id', $user->id);
        }
        
        // Filter by search term
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('teacher', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }
        
        $classes = $query->withCount('students')->latest()->paginate(10)->withQueryString();
        
        return Inertia::render('classes/index', [
            'classes' => $classes,
            'filters' => $request->only(['search', 'is_active']),
            'user_role' => $user->role,
        ]);
    }

    /**
     * Show the form for creating a new class.
     */
    public function create()
    {
        $teachers = User::teachers()->get();
        
        return Inertia::render('classes/create', [
            'teachers' => $teachers,
        ]);
    }

    /**
     * Store a newly created class in storage.
     */
    public function store(StoreSchoolClassRequest $request)
    {
        $class = SchoolClass::create($request->validated());

        return redirect()->route('classes.show', $class)
            ->with('success', 'Class created successfully.');
    }

    /**
     * Display the specified class.
     */
    public function show(SchoolClass $class, Request $request)
    {
        $class->load(['teacher', 'students' => function ($query) {
            $query->active()->orderBy('first_name');
        }]);
        
        // Get recent attendance for this class
        $recent_attendance = $class->attendanceRecords()
            ->with(['student', 'markedBy'])
            ->latest('attendance_date')
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->groupBy('attendance_date');
        
        // Calculate class statistics
        $total_students = $class->students->count();
        $today_attendance = $class->attendanceRecords()
            ->where('attendance_date', today())
            ->count();
        $today_present = $class->attendanceRecords()
            ->where('attendance_date', today())
            ->where('status', 'present')
            ->count();
        
        $attendance_percentage = $today_attendance > 0 ? 
            round(($today_present / $today_attendance) * 100, 1) : 0;
        
        return Inertia::render('classes/show', [
            'class' => $class,
            'recent_attendance' => $recent_attendance,
            'class_stats' => [
                'total_students' => $total_students,
                'today_attendance' => $today_attendance,
                'today_present' => $today_present,
                'today_absent' => $today_attendance - $today_present,
                'attendance_percentage' => $attendance_percentage,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit(SchoolClass $class)
    {
        $teachers = User::teachers()->get();
        
        return Inertia::render('classes/edit', [
            'class' => $class,
            'teachers' => $teachers,
        ]);
    }

    /**
     * Update the specified class in storage.
     */
    public function update(UpdateSchoolClassRequest $request, SchoolClass $class)
    {
        $class->update($request->validated());

        return redirect()->route('classes.show', $class)
            ->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}