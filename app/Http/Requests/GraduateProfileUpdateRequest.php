<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GraduateProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'university' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            'degree' => 'nullable|in:diploma,bachelor,master,phd',
            'graduation_date' => 'nullable|date',
            'graduation_year' => 'nullable|integer|min:2000|max:' . now()->year,
            'gpa' => 'nullable|numeric|min:0|max:4',
            'region' => 'nullable|string|max:100',
            'employment_status' => 'nullable|in:employed,self_employed,unemployed,freelancer',
            'job_title' => 'nullable|string|max:255',
            'expected_salary' => 'nullable|integer|min:0',
            'experience_years' => 'nullable|integer|min:0',
            'linkedin' => 'nullable|url|max:500',
            'skills' => 'nullable|string|max:1000',
            'languages' => 'nullable|string|max:500',
            'certifications' => 'nullable|string|max:500',
            'job_preferences' => 'nullable|string|max:500',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ];
    }
}
