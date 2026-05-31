<?php

namespace App\Http\Requests\Graduate;

use Illuminate\Foundation\Http\FormRequest;

class AcademicVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'index_number' => ['required', 'string', 'min:3', 'max:50'],
            'registration_number' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'index_number.required' => 'Index number is required.',
            'index_number.string' => 'Index number must be text.',
            'index_number.min' => 'Index number must be at least 3 characters.',
            'index_number.max' => 'Index number must not exceed 50 characters.',
            'registration_number.max' => 'Registration number must not exceed 50 characters.',
        ];
    }
}
