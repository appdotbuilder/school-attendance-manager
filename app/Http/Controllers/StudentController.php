<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index(Request $request)
    {
        $query = Student::with(['schoolClass', 'schoolClass.teacher']);
        
        // Filter by search term
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }
        
        // Filter by class
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $students = $query->latest()->paginate(10)->withQueryString();
        $classes = SchoolClass::active()->with('teacher')->get();
        
        return Inertia::render('students/index', [
            'students' => $students,
            'classes' => $classes,
            'filters' => $request->only(['search', 'class_id', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $classes = SchoolClass::active()->with('teacher')->get();
        
        return Inertia::render('students/create', [
            'classes' => $classes,
        ]);
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $student = Student::create($request->validated());

        return redirect()->route('students.show', $student)
            ->with('success', 'Student registered successfully.');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student, Request $request)
    {
        $student->load(['schoolClass', 'schoolClass.teacher']);
        
        // Get attendance records for the student
        $attendance_query = $student->attendanceRecords()
            ->with(['schoolClass', 'markedBy'])
            ->latest('attendance_date');
            
        // Filter by date range if provided
        if ($request->filled('from_date')) {
            $attendance_query->where('attendance_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $attendance_query->where('attendance_date', '<=', $request->to_date);
        }
        
        $attendance_records = $attendance_query->paginate(10)->withQueryString();
        
        // Calculate attendance statistics
        $total_records = $student->attendanceRecords()->count();
        $present_count = $student->attendanceRecords()->where('status', 'present')->count();
        $attendance_percentage = $total_records > 0 ? round(($present_count / $total_records) * 100, 1) : 0;
        
        return Inertia::render('students/show', [
            'student' => $student,
            'attendance_records' => $attendance_records,
            'attendance_stats' => [
                'total_records' => $total_records,
                'present_count' => $present_count,
                'absent_count' => $total_records - $present_count,
                'attendance_percentage' => $attendance_percentage,
            ],
            'filters' => $request->only(['from_date', 'to_date']),
        ]);
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $classes = SchoolClass::active()->with('teacher')->get();
        
        return Inertia::render('students/edit', [
            'student' => $student,
            'classes' => $classes,
        ]);
    }

    /**
     * Update the specified student in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}