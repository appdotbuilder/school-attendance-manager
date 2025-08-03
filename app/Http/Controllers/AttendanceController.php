<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendance records.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = AttendanceRecord::with(['student', 'schoolClass', 'markedBy']);
        
        // Teachers can only see attendance for their classes
        if ($user->role === 'teacher') {
            $teacher_class_ids = SchoolClass::where('teacher_id', $user->id)->pluck('id');
            $query->whereIn('class_id', $teacher_class_ids);
        }
        
        // Filter by date
        if ($request->filled('date')) {
            $query->forDate($request->date);
        } else {
            // Default to today's attendance
            $query->forDate(today());
        }
        
        // Filter by class
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $attendance_records = $query->latest('created_at')->paginate(15)->withQueryString();
        
        // Get classes for filter dropdown
        $classes = $user->role === 'administrator' 
            ? SchoolClass::active()->with('teacher')->get()
            : SchoolClass::where('teacher_id', $user->id)->active()->get();
        
        return Inertia::render('attendance/index', [
            'attendance_records' => $attendance_records,
            'classes' => $classes,
            'filters' => $request->only(['date', 'class_id', 'status']),
            'user_role' => $user->role,
        ]);
    }

    /**
     * Show the form for creating new attendance records.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        
        // Get classes based on user role
        $classes = $user->role === 'administrator' 
            ? SchoolClass::active()->with('teacher')->get()
            : SchoolClass::where('teacher_id', $user->id)->active()->get();
        
        $selected_class = null;
        $students = collect();
        $existing_attendance = collect();
        
        // If class is selected, get students and existing attendance
        if ($request->filled('class_id')) {
            $selected_class = SchoolClass::with('teacher')->findOrFail($request->class_id);
            $students = Student::where('class_id', $selected_class->id)
                ->active()
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();
            
            // Get existing attendance for today
            $attendance_date = $request->get('date', today()->format('Y-m-d'));
            $existing_attendance = AttendanceRecord::where('class_id', $selected_class->id)
                ->forDate($attendance_date)
                ->get()
                ->keyBy('student_id');
        }
        
        return Inertia::render('attendance/create', [
            'classes' => $classes,
            'selected_class' => $selected_class,
            'students' => $students,
            'existing_attendance' => $existing_attendance,
            'selected_date' => $request->get('date', today()->format('Y-m-d')),
            'user_role' => $user->role,
        ]);
    }

    /**
     * Store newly created attendance records in storage.
     */
    public function store(StoreAttendanceRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();
        
        foreach ($validated['attendance'] as $attendance_data) {
            // Check if attendance already exists for this student and date
            $existing = AttendanceRecord::where('student_id', $attendance_data['student_id'])
                ->forDate($validated['attendance_date'])
                ->first();
            
            if ($existing) {
                // Update existing record
                $existing->update([
                    'status' => $attendance_data['status'],
                    'notes' => $attendance_data['notes'] ?? null,
                    'marked_by' => $user->id,
                    'marked_at_time' => now()->format('H:i'),
                ]);
            } else {
                // Create new record
                AttendanceRecord::create([
                    'student_id' => $attendance_data['student_id'],
                    'class_id' => $validated['class_id'],
                    'attendance_date' => $validated['attendance_date'],
                    'status' => $attendance_data['status'],
                    'notes' => $attendance_data['notes'] ?? null,
                    'marked_by' => $user->id,
                    'marked_at_time' => now()->format('H:i'),
                ]);
            }
        }

        return redirect()->route('attendance.index', [
            'date' => $validated['attendance_date'],
            'class_id' => $validated['class_id']
        ])->with('success', 'Attendance marked successfully.');
    }

    /**
     * Display the specified attendance record.
     */
    public function show(AttendanceRecord $attendance)
    {
        $attendance->load(['student', 'schoolClass', 'markedBy']);
        
        return Inertia::render('attendance/show', [
            'attendance' => $attendance,
        ]);
    }

    /**
     * Show the form for editing the specified attendance record.
     */
    public function edit(AttendanceRecord $attendance)
    {
        $attendance->load(['student', 'schoolClass']);
        
        return Inertia::render('attendance/edit', [
            'attendance' => $attendance,
        ]);
    }

    /**
     * Update the specified attendance record in storage.
     */
    public function update(Request $request, AttendanceRecord $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $attendance->update([
            ...$validated,
            'marked_by' => $request->user()->id,
            'marked_at_time' => now()->format('H:i'),
        ]);

        return redirect()->route('attendance.show', $attendance)
            ->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified attendance record from storage.
     */
    public function destroy(AttendanceRecord $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance record deleted successfully.');
    }
}