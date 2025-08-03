<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolClassRequest extends FormRequest
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
            'name' => 'required|string|max:100|unique:classes,name,' . $this->route('class')->id,
            'description' => 'nullable|string|max:500',
            'teacher_id' => 'required|exists:users,id',
            'capacity' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
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
            'name.required' => 'Class name is required.',
            'name.unique' => 'A class with this name already exists.',
            'teacher_id.required' => 'Please select a teacher for this class.',
            'teacher_id.exists' => 'Selected teacher does not exist.',
            'capacity.required' => 'Class capacity is required.',
            'capacity.min' => 'Class capacity must be at least 1.',
            'capacity.max' => 'Class capacity cannot exceed 100.',
        ];
    }
}