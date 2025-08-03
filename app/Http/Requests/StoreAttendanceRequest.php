<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attendance_date' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'attendance_date.required' => 'Attendance date is required.',
            'class_id.required' => 'Class selection is required.',
            'class_id.exists' => 'Selected class does not exist.',
            'attendance.required' => 'Attendance data is required.',
            'attendance.*.student_id.required' => 'Student ID is required for each attendance record.',
            'attendance.*.student_id.exists' => 'One or more selected students do not exist.',
            'attendance.*.status.required' => 'Attendance status is required for each student.',
            'attendance.*.status.in' => 'Invalid attendance status selected.',
        ];
    }
}