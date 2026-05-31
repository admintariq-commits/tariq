<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterGraduateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'graduation_date' => 'required|date',
            'national_id' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'university' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'degree' => 'nullable|in:diploma,bachelor,master,phd',
            'graduation_year' => 'nullable|integer|min:2000|max:' . now()->year,
            'gpa' => 'nullable|numeric|min:0|max:4',
            'region' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'detected_region' => 'nullable|string|max:255',
            'location_source' => 'nullable|string|max:100',
            'region_match' => 'nullable|boolean',
            'location_accuracy' => 'nullable|string|max:100',
            'employment_status' => 'nullable|in:employed,self_employed,unemployed,freelancer',
            'job_title' => 'nullable|string|max:255',
            'expected_salary' => 'nullable|integer|min:0',
            'experience_years' => 'nullable|integer|min:0',
            'linkedin' => 'nullable|url|max:500',
            'skills' => 'nullable|string|max:1000',
            'languages' => 'nullable|string|max:500',
            'certifications' => 'nullable|string|max:500',
            'job_preferences' => 'nullable|string|max:500',
            'resume' => 'required|file|mimes:pdf,doc,docx|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:5120',
            'terms' => 'required|accepted',
        ];
    }

    public function messages()
    {
        return [
            'terms.accepted' => 'You must accept the Terms and Privacy Policy.',
            'resume.mimes' => 'Resume must be a PDF, DOC, or DOCX document.',
            'resume.mimetypes' => 'Resume must be a valid PDF, DOC, or DOCX file.',
            'resume.max' => 'Resume file size must not exceed 5MB.',
        ];
    }
}
