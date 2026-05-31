<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - TARIQ</title>
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

    <div class="max-w-6xl mx-auto p-6">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-3xl font-bold text-slate-900">My Profile</h2>
            <a href="{{ route('graduate.profile.edit') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-purple-700 transition">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6" role="alert">
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-slate-500 uppercase mb-2">Employment Status</h3>
                <p class="text-2xl font-bold text-purple-600">{{ ucfirst($graduate->employment_status ?? 'Not specified') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-slate-500 uppercase mb-2">Years of Experience</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $graduate->experience_years ?? 0 }} years</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-slate-500 uppercase mb-2">Employability Score</h3>
                <p class="text-3xl font-bold text-indigo-600">{{ $graduate->employability_score ?? 0 }}%</p>
                <div class="w-full bg-slate-200 rounded-full h-2 mt-3">
                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $graduate->employability_score ?? 0 }}%"></div>
                </div>
                <p class="text-sm text-slate-600 mt-2">{{ $graduate->career_readiness ?? 'Profile needs review' }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-slate-500 uppercase mb-2">Profile Completion</h3>
                <div class="w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $graduate->getCompletionPercentage() ?? 60 }}%"></div>
                </div>
                <p class="text-sm text-slate-600 mt-2">{{ $graduate->getCompletionPercentage() ?? 60 }}% complete</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-purple-600"></i> Personal Information
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-slate-500 font-semibold">Full Name</p>
                        <p class="text-slate-900">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Email</p>
                        <p class="text-slate-900">{{ Auth::user()->email }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Phone</p>
                        <p class="text-slate-900">{{ $graduate->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Gender</p>
                        <p class="text-slate-900">{{ ucfirst($graduate->gender ?? 'Not specified') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">National ID</p>
                        <p class="text-slate-900">{{ $graduate->national_id ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Region</p>
                        <p class="text-slate-900">{{ $graduate->region ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-book text-blue-600"></i> Academic Information
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-slate-500 font-semibold">University</p>
                        <p class="text-slate-900">{{ $graduate->university ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Course</p>
                        <p class="text-slate-900">{{ $graduate->course ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Degree</p>
                        <p class="text-slate-900">{{ ucfirst($graduate->degree ?? 'Not specified') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Graduation Date</p>
                        <p class="text-slate-900">{{ $graduate->graduation_date ? $graduate->graduation_date->format('M d, Y') : 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">GPA</p>
                        <p class="text-slate-900">{{ $graduate->gpa ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Graduation Year</p>
                        <p class="text-slate-900">{{ $graduate->graduation_year ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-briefcase text-green-600"></i> Professional Information
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-slate-500 font-semibold">Employment Status</p>
                        <p class="text-slate-900">{{ ucfirst(str_replace('_', ' ', $graduate->employment_status ?? 'Not specified')) }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Current Job Title</p>
                        <p class="text-slate-900">{{ $graduate->job_title ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Years of Experience</p>
                        <p class="text-slate-900">{{ $graduate->experience_years ?? 0 }} years</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Expected Salary (TZS)</p>
                        <p class="text-slate-900">{{ $graduate->expected_salary ? number_format($graduate->expected_salary) : 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">LinkedIn Profile</p>
                        <p class="text-slate-900">
                            @if($graduate->linkedin)
                                <a href="{{ $graduate->linkedin }}" target="_blank" class="text-blue-600 hover:underline">
                                    <i class="fab fa-linkedin"></i> View Profile
                                </a>
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Skills & Qualifications -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-star text-amber-600"></i> Skills & Qualifications
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-slate-500 font-semibold">Skills</p>
                        <p class="text-slate-900 whitespace-pre-wrap">{{ $graduate->skills ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Languages</p>
                        <p class="text-slate-900 whitespace-pre-wrap">{{ $graduate->languages ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Certifications</p>
                        <p class="text-slate-900 whitespace-pre-wrap">{{ $graduate->certifications ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-semibold">Job Preferences</p>
                        <p class="text-slate-900 whitespace-pre-wrap">{{ $graduate->job_preferences ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6 border border-indigo-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Career Support Insights</h3>
                    <p class="text-sm text-slate-500">Unique insights to help you stand out in Tanzania's job market.</p>
                </div>
                <span class="text-xs uppercase tracking-[0.18em] text-indigo-600 font-bold">Smart matching</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-indigo-50 rounded-2xl p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-2">Time Unemployed</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $graduate->months_unemployed }} months</p>
                    <p class="text-slate-600 text-sm mt-2">Based on graduation or last job held.</p>
                </div>
                <div class="bg-emerald-50 rounded-2xl p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-2">Current Strength</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $graduate->career_readiness ?? 'Not assessed' }}</p>
                    <p class="text-slate-600 text-sm mt-2">Shows your current readiness for fast placement.</p>
                </div>
                <div class="bg-amber-50 rounded-2xl p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-2">Best Next Step</p>
                    <p class="text-slate-900 font-semibold">{{ $graduate->employment_status === 'unemployed' ? 'Apply to more jobs and complete skills training' : 'Keep updating your profile' }}</p>
                    <p class="text-slate-600 text-sm mt-2">Suggested action for higher job matches.</p>
                </div>
            </div>
        </div>

        <!-- Resume Section -->
        @if($graduate->resume_path)
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-file-pdf text-red-600"></i> Resume
                </h3>
                <p class="text-sm text-slate-600 mb-3">Your resume is on file:</p>
                <p class="text-slate-900 font-mono text-sm break-all">{{ $graduate->resume_path }}</p>
            </div>
        @endif

        <!-- Actions -->
        <div class="mt-6 flex gap-3">
            <a href="{{ route('graduate.profile.edit') }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg flex items-center gap-2 hover:bg-purple-700 transition">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
            <a href="{{ route('graduate.job-matches') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700 transition">
                <i class="fas fa-briefcase"></i> View Job Matches
            </a>
            <a href="{{ route('graduate.applications') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg flex items-center gap-2 hover:bg-green-700 transition">
                <i class="fas fa-check-circle"></i> My Applications
            </a>
        </div>
    </div>
</body>
</html>
