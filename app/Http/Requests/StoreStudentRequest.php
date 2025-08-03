<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'student_id' => 'required|string|unique:students,student_id|max:20',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'parent_name' => 'nullable|string|max:200',
            'parent_phone' => 'nullable|string|max:20',
            'parent_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'class_id' => 'nullable|exists:classes,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,inactive,transferred',
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
            'student_id.required' => 'Student ID is required.',
            'student_id.unique' => 'This Student ID is already registered.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'gender.required' => 'Gender is required.',
            'parent_email.email' => 'Please provide a valid parent email address.',
            'class_id.exists' => 'Selected class does not exist.',
            'enrollment_date.required' => 'Enrollment date is required.',
        ];
    }
}