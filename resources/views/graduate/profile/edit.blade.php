<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - TARIQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-purple-600"><i class="fas fa-graduation-cap mr-2"></i>TARIQ</h1>
            <div class="flex items-center gap-4">
                <span class="text-slate-700">{{ Auth::user()->email }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-700">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto p-6">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-3xl font-bold text-slate-900">Edit Profile</h2>
            <a href="{{ route('graduate.profile') }}" class="text-slate-600 hover:text-slate-900">
                <i class="fas fa-arrow-left mr-1"></i> Back to Profile
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-semibold">Please fix the errors below:</p>
                <ul class="list-disc pl-5 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('graduate.profile.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-purple-600"></i> Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Phone *</label>
                        <input type="text" name="phone" value="{{ old('phone', $graduate->phone) }}" 
                            class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">National ID</label>
                        <input type="text" name="national_id" value="{{ old('national_id', $graduate->national_id) }}" 
                            class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Gender</label>
                        <select name="gender" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select...</option>
                            <option value="male" {{ old('gender', $graduate->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $graduate->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $graduate->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Region</label>
                        <select name="region" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select Region...</option>
                            <option value="Dar es Salaam" {{ old('region', $graduate->region) === 'Dar es Salaam' ? 'selected' : '' }}>Dar es Salaam</option>
                            <option value="Arusha" {{ old('region', $graduate->region) === 'Arusha' ? 'selected' : '' }}>Arusha</option>
                            <option value="Dodoma" {{ old('region', $graduate->region) === 'Dodoma' ? 'selected' : '' }}>Dodoma</option>
                            <option value="Mbeya" {{ old('region', $graduate->region) === 'Mbeya' ? 'selected' : '' }}>Mbeya</option>
                            <option value="Kilimanjaro" {{ old('region', $graduate->region) === 'Kilimanjaro' ? 'selected' : '' }}>Kilimanjaro</option>
                            <option value="Morogoro" {{ old('region', $graduate->region) === 'Morogoro' ? 'selected' : '' }}>Morogoro</option>
                            <option value="Mwanza" {{ old('region', $graduate->region) === 'Mwanza' ? 'selected' : '' }}>Mwanza</option>
                            <option value="Tanga" {{ old('region', $graduate->region) === 'Tanga' ? 'selected' : '' }}>Tanga</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-book text-blue-600"></i> Academic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">University</label>
                        <input type="text" name="university" value="{{ old('university', $graduate->university) }}" 
                            class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Course</label>
                        <input type="text" name="course" value="{{ old('course', $graduate->course) }}" 
                            class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Degree Type</label>
                        <select name="degree" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select...</option>
                            <option value="diploma" {{ old('degree', $graduate->degree) === 'diploma' ? 'selected' : '' }}>Diploma</option>
                            <option value="bachelor" {{ old('degree', $graduate->degree) === 'bachelor' ? 'selected' : '' }}>Bachelor's</option>
                            <option value="master" {{ old('degree', $graduate->degree) === 'master' ? 'selected' : '' }}>Master's</option>
                            <option value="phd" {{ old('degree', $graduate->degree) === 'phd' ? 'selected' : '' }}>PhD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Graduation Date</label>
                        <input type="date" name="graduation_date" value="{{ old('graduation_date', $graduate->graduation_date?->format('Y-m-d')) }}" 
                            class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">GPA</label>
                        <input type="number" name="gpa" value="{{ old('gpa', $graduate->gpa) }}" step="0.01" min="0" max="4"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="e.g. 3.5">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Graduation Year</label>
                        <select name="graduation_year" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select...</option>
                            @for ($year = date('Y'); $year >= 2015; $year--)
                                <option value="{{ $year }}" {{ old('graduation_year', $graduate->graduation_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-briefcase text-green-600"></i> Professional Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Employment Status</label>
                        <select name="employment_status" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select...</option>
                            <option value="employed" {{ old('employment_status', $graduate->employment_status) === 'employed' ? 'selected' : '' }}>Employed</option>
                            <option value="self_employed" {{ old('employment_status', $graduate->employment_status) === 'self_employed' ? 'selected' : '' }}>Self Employed</option>
                            <option value="unemployed" {{ old('employment_status', $graduate->employment_status) === 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                            <option value="freelancer" {{ old('employment_status', $graduate->employment_status) === 'freelancer' ? 'selected' : '' }}>Freelancer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Job Title</label>
                        <input type="text" name="job_title" value="{{ old('job_title', $graduate->job_title) }}" 
                            class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Years of Experience</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years', $graduate->experience_years) }}" min="0"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Expected Salary (TZS)</label>
                        <input type="number" name="expected_salary" value="{{ old('expected_salary', $graduate->expected_salary) }}" 
                            class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="e.g. 2000000">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-slate-700 text-sm font-semibold mb-2">LinkedIn Profile</label>
                        <input type="url" name="linkedin" value="{{ old('linkedin', $graduate->linkedin) }}" 
                            class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="https://linkedin.com/in/...">
                    </div>
                </div>
            </div>

            <!-- Skills & Qualifications -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-star text-amber-600"></i> Skills & Qualifications
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Skills (comma-separated)</label>
                        <textarea name="skills" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="e.g. Python, Data Analysis, Project Management">{{ old('skills', $graduate->skills) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Languages</label>
                        <textarea name="languages" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="e.g. Swahili, English, French">{{ old('languages', $graduate->languages) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Certifications</label>
                        <textarea name="certifications" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="e.g. AWS Certified, PMP, CPA">{{ old('certifications', $graduate->certifications) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Job Preferences</label>
                        <textarea name="job_preferences" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="e.g. Remote work, Full-time, Relocatable">{{ old('job_preferences', $graduate->job_preferences) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Resume Upload -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-file-upload text-sky-600"></i> Resume
                </h3>
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2">Upload CV / Resume (PDF, DOC, DOCX)</label>
                    <input type="file" name="resume" accept=".pdf,.doc,.docx" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <p class="text-xs text-slate-500 mt-2">Max 5MB. Current: {{ $graduate->resume ? 'On file' : 'Not uploaded' }}</p>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex gap-3 justify-end">
                <a href="{{ route('graduate.profile') }}" class="px-6 py-2 border-2 border-slate-300 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</body>
</html>
