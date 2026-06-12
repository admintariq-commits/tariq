<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graduate Registration - TARIQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        
        .step-indicator { display: flex; align-items: center; gap: 1rem; overflow-x: auto; padding: 0.5rem 0; }
        .step-item { display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0; }
        .step-number { display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 50%; background: #e2e8f0; color: #64748b; font-weight: bold; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .step-number.active { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4); transform: scale(1.05); }
        .step-number.completed { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3); }
        .step-line { flex: 1; height: 3px; background: #e2e8f0; transition: all 0.3s ease; min-width: 30px; }
        .step-line.active { background: linear-gradient(to right, #4f46e5, #7c3aed); }
        
        .form-section { display: none; opacity: 0; transform: translateY(10px); transition: all 0.3s ease; }
        .form-section.active { display: block; opacity: 1; transform: translateY(0); }
        
        .input-field { transition: all 0.3s ease; }
        .input-field:focus { outline: none; border-color: #4f46e5 !important; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15) !important; }
        
        .form-card { background: rgba(255,255,255,0.96); border: 1px solid rgba(99,102,241,0.14); box-shadow: 0 45px 120px rgba(15, 23, 42, 0.08); backdrop-filter: blur(10px); }
        
        .btn-primary { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); transition: all 0.3s ease; position: relative; overflow: hidden; }
        .btn-primary:hover:not(:disabled) { box-shadow: 0 15px 40px -5px rgba(79, 70, 229, 0.45); transform: translateY(-2px); }
        .btn-primary:disabled { opacity: 0.55; cursor: not-allowed; }

        .btn-secondary { background: #eef2ff; color: #4338ca; }
        .btn-secondary:hover:not(:disabled) { background: #e0e7ff; }
        
        .section-icon { display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 18px; font-size: 20px; box-shadow: inset 0 0 0 1px rgba(79,70,229,0.12); }
        
        .field-group { animation: fadeInUp 0.45s ease-out backwards; background: #fff; border: 1px solid rgba(148,163,184,0.18); border-radius: 1.25rem; padding: 1rem 1.1rem; box-shadow: 0 10px 30px rgba(15,23,42,0.04); }
        .field-group label { display: inline-flex; align-items: center; gap: 0.65rem; margin-bottom: 0.8rem; color: #334155; }
        .field-group input, .field-group select, .field-group textarea { background: #f8fafc; border-color: rgba(148,163,184,0.3); }
        .field-group input:hover, .field-group select:hover, .field-group textarea:hover { border-color: rgba(99,102,241,0.4); }
        .field-group input:focus, .field-group select:focus, .field-group textarea:focus { background: #ffffff; }
        
        .section-title { background: rgba(99,102,241,0.08); border-radius: 1.5rem; padding: 1rem 1.15rem; }
        .section-title h2 { margin: 0; }
        .progress-pill { display: inline-flex; align-items: center; gap: 0.75rem; background: rgba(79, 70, 229, 0.08); color: #3730a3; border-radius: 9999px; padding: 0.8rem 1rem; font-size: 0.95rem; margin-top: 1rem; }
        .progress-pill .bar { flex: 1; height: 6px; background: rgba(79,70,229,0.12); border-radius: 9999px; overflow: hidden; }
        .progress-pill .bar-fill { height: 100%; background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%); transition: width 0.3s ease; }
        .completion-pill { display: inline-flex; align-items: center; gap: 0.6rem; background: rgba(16, 185, 129, 0.1); color: #0f766e; border-radius: 9999px; padding: 0.75rem 1rem; font-size: 0.95rem; margin-top: 1rem; margin-left: 1rem; }
        .completion-pill strong { color: #115e59; }
        
        .validation-error { animation: shake 0.4s ease-out; }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="min-h-screen py-12 px-4" x-data="registrationForm()">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="w-20 h-20 bg-gradient-to-br from-indigo-600 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-graduation-cap text-white text-4xl"></i>
                </div>
                <h1 class="text-5xl font-bold text-slate-900 mb-2">Graduate Registration</h1>
                <p class="text-slate-600 text-lg">Join TARIQ and unlock career opportunities</p>
            </div>

            <!-- Progress Indicator -->
            <div class="mb-12 bg-white rounded-2xl p-8 shadow-lg border border-slate-100">
                <div class="step-indicator">
                    <template x-for="(step, index) in steps" :key="index">
                        <div class="step-item" style="flex: 1;">
                            <div class="step-number" :class="{ 'active': currentStep === index, 'completed': currentStep > index }">
                                <template x-if="currentStep > index">
                                    <i class="fas fa-check text-sm"></i>
                                </template>
                                <template x-if="currentStep <= index">
                                    <span x-text="index + 1"></span>
                                </template>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider" x-text="step.label"></p>
                                <p class="text-sm font-semibold text-slate-800" x-text="step.title"></p>
                            </div>
                            <div class="step-line" :class="{ 'active': currentStep > index }" x-show="index < steps.length - 1"></div>
                        </div>
                    </template>
                </div>
                <div class="flex flex-wrap items-center gap-3 mt-4">
                    <div class="progress-pill">
                        <span x-text="progressLabel()"></span>
                        <div class="bar">
                            <div class="bar-fill" :style="`width: ${progressPercent()}%`"></div>
                        </div>
                    </div>
                    <div class="completion-pill">
                        <span x-text="completionLabel()"></span>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <style>
                #regForm .field-group {
                    padding: 1rem;
                    background-color: #fbfcfe;
                    border: 1px solid rgba(148,163,184,.18);
                    border-radius: 1.5rem;
                    transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
                }
                #regForm .field-group:hover {
                    transform: translateY(-1px);
                    border-color: rgba(99,102,241,.32);
                    box-shadow: 0 12px 30px rgba(15,23,42,.08);
                }
                #regForm .field-group label {
                    color: #0f172a;
                    font-size: .75rem;
                    font-weight: 700;
                    letter-spacing: .12em;
                    text-transform: uppercase;
                }
                #regForm .input-field {
                    width: 100%;
                    background-color: #f8fafc;
                    border: 1px solid #cbd5e1;
                    border-radius: 1.5rem;
                    padding: 1rem 1.25rem;
                    color: #0f172a;
                    box-shadow: inset 0 1px 2px rgba(15,23,42,.04);
                    transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
                }
                #regForm .input-field:focus {
                    outline: none;
                    border-color: #6366f1;
                    box-shadow: 0 0 0 4px rgba(99,102,241,.15);
                    transform: translateY(-1px);
                }
                #regForm select.input-field,
                #regForm textarea.input-field {
                    line-height: 1.6;
                }
            </style>
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 border border-slate-100 form-card">
                <form method="POST" action="{{ route('graduate.register.post') }}" class="space-y-6" enctype="multipart/form-data" id="regForm" @input="refreshCompletion()">
                    @csrf

                    <!-- Section 1: Personal Information -->
                    <div class="form-section active" data-step="0">
                        <div class="mb-8 section-title">
                            <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                                Personal Information
                            </h2>
                            <p class="text-slate-600 ml-13">Tell us about yourself</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="field-group">
                                <label class="block text-slate-900 text-xs font-semibold uppercase tracking-[0.12em] mb-3 flex items-center gap-2">
                                    <div class="w-5 h-5 bg-indigo-100 rounded-full flex items-center justify-center"><i class="fas fa-user-circle text-indigo-600 text-xs"></i></div>
                                    First Name *
                                </label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full bg-slate-50 border border-slate-200 rounded-3xl px-5 py-3 input-field focus:outline-none focus:ring-2 focus:ring-indigo-300 shadow-sm transition duration-200 hover:border-slate-400" required>
                            </div>
                            <div class="field-group">
                                <label class="block text-slate-800 text-sm font-bold mb-3 flex items-center gap-2">
                                    <div class="w-5 h-5 bg-indigo-100 rounded flex items-center justify-center"><i class="fas fa-user-circle text-indigo-600 text-xs"></i></div>
                                    Last Name *
                                </label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full border-2 border-slate-200 rounded-xl px-5 py-3 input-field focus:ring-0 transition hover:border-slate-300" required>
                            </div>
                            <div class="field-group">
                                <label class="block text-slate-800 text-sm font-bold mb-3 flex items-center gap-2">
                                    <div class="w-5 h-5 bg-indigo-100 rounded flex items-center justify-center"><i class="fas fa-envelope text-indigo-600 text-xs"></i></div>
                                    Email *
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full border-2 border-slate-200 rounded-xl px-5 py-3 input-field focus:ring-0 transition hover:border-slate-300" required>
                            </div>
                            <div class="field-group">
                                <label class="block text-slate-800 text-sm font-bold mb-3 flex items-center gap-2">
                                    <div class="w-5 h-5 bg-indigo-100 rounded flex items-center justify-center"><i class="fas fa-phone text-indigo-600 text-xs"></i></div>
                                    Phone *
                                </label>
                                <div class="flex gap-2">
                                    <input type="text" id="phoneInput" name="phone" value="{{ old('phone') }}" class="flex-1 border-2 border-slate-200 rounded-xl px-5 py-3 input-field focus:ring-0 transition hover:border-slate-300" placeholder="+255 712 345 678" required>
                                    <button type="button" id="sendOtpBtn" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition btn-primary">Send OTP</button>
                                </div>
                                <p id="otpNotice" class="text-xs text-emerald-600 mt-3 hidden flex items-center gap-1"><i class="fas fa-check-circle"></i> OTP sent (dev mode shows code).</p>
                            </div>
                            <div class="field-group">
                                <label class="block text-slate-800 text-sm font-bold mb-3 flex items-center gap-2">
                                    <div class="w-5 h-5 bg-indigo-100 rounded flex items-center justify-center"><i class="fas fa-id-card text-indigo-600 text-xs"></i></div>
                                    National ID / Passport *
                                </label>
                                <input type="text" name="national_id" value="{{ old('national_id') }}" class="w-full border-2 border-slate-200 rounded-xl px-5 py-3 input-field focus:ring-0 transition hover:border-slate-300" required>
                            </div>
                            <div class="field-group">
                                <label class="block text-slate-800 text-sm font-bold mb-3 flex items-center gap-2">
                                    <div class="w-5 h-5 bg-indigo-100 rounded flex items-center justify-center"><i class="fas fa-venus-mars text-indigo-600 text-xs"></i></div>
                                    Gender
                                </label>
                                <select name="gender" class="w-full border-2 border-slate-200 rounded-xl px-5 py-3 input-field focus:ring-0 transition hover:border-slate-300">
                                    <option value="">Select...</option>
                                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Resume Upload + OTP Verification (stays in Section 1) -->
                    <div class="border-t-2 border-slate-100 pt-8 mt-8 section-title">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-3">
                            <div class="section-icon bg-amber-100"><i class="fas fa-file-pdf text-amber-600"></i></div>
                            CV & Phone Verification
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="field-group">
                                <label class="block text-slate-800 text-sm font-bold mb-3 flex items-center gap-2">
                                    <i class="fas fa-file text-slate-600"></i> Upload CV / Resume *
                                </label>
                                <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx" class="w-full border-2 border-dashed border-slate-300 rounded-xl px-5 py-6 input-field focus:ring-0 transition hover:border-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" required>
                                <p class="text-xs text-slate-500 mt-2"><i class="fas fa-info-circle"></i> PDF, DOC, or DOCX only. File must be under 5MB and correctly typed.</p>
                                <p id="resumeError" class="text-xs text-red-600 mt-2 hidden"></p>
                            </div>
                            <div class="field-group">
                                <label class="block text-slate-800 text-sm font-bold mb-3 flex items-center gap-2">
                                    <i class="fas fa-lock text-slate-600"></i> Enter OTP
                                </label>
                                <div class="flex gap-2">
                                    <input type="text" id="otpInput" class="flex-1 border-2 border-slate-200 rounded-xl px-5 py-3 input-field focus:ring-0 transition hover:border-slate-300" placeholder="000000" disabled>
                                    <button type="button" id="verifyOtpBtn" class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition btn-primary" disabled>Verify</button>
                                </div>
                                <p id="otpResult" class="text-sm mt-3 font-medium"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Academic Information -->
                    <div class="form-section" data-step="1">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-book text-pink-600"></i>
                                </div>
                                Academic Information
                            </h2>
                            <p class="text-slate-600 ml-13">Your educational background</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-slate-900 text-xs font-semibold uppercase tracking-[0.12em] mb-3">University / Institution *</label>
                                <select name="university" class="w-full bg-slate-50 border border-slate-300 rounded-3xl px-5 py-3 input-field focus:outline-none focus:ring-2 focus:ring-indigo-300 shadow-sm transition duration-200 hover:border-slate-400" required>
                                    <option value="">Select your institution</option>
                                    @foreach($universities as $university)
                                        <option value="{{ $university->name }}" {{ old('university') === $university->name ? 'selected' : '' }}>{{ $university->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-slate-500 mt-2">Choose your institution from the list of Tanzanian universities.</p>
                            </div>
                            <div>
                                <label class="block text-slate-900 text-xs font-semibold uppercase tracking-[0.12em] mb-3">Course / Program *</label>
                                <select name="course" class="w-full bg-slate-50 border border-slate-300 rounded-3xl px-5 py-3 input-field focus:outline-none focus:ring-2 focus:ring-indigo-300 shadow-sm transition duration-200 hover:border-slate-400" required>
                                    <option value="">Select your course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->name }}" {{ old('course') === $course->name ? 'selected' : '' }}>{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Degree Type *</label>
                                <select name="degree" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" required>
                                    <option value="">Select...</option>
                                    <option value="diploma" {{ old('degree') === 'diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="bachelor" {{ old('degree') === 'bachelor' ? 'selected' : '' }}>Bachelor's Degree</option>
                                    <option value="master" {{ old('degree') === 'master' ? 'selected' : '' }}>Master's Degree</option>
                                    <option value="phd" {{ old('degree') === 'phd' ? 'selected' : '' }}>PhD</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Graduation Date *</label>
                                <input type="date" name="graduation_date" value="{{ old('graduation_date') }}" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">GPA / Grade</label>
                                <input type="number" name="gpa" value="{{ old('gpa') }}" step="0.01" min="0" max="4" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" placeholder="e.g. 3.5">
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Year of Graduation *</label>
                                <select name="graduation_year" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" required>
                                    <option value="">Select...</option>
                                    @for ($year = date('Y'); $year >= 2015; $year--)
                                        <option value="{{ $year }}" {{ old('graduation_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Geographic & Career Information -->
                    <div class="form-section" data-step="2">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                                <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-map-pin text-cyan-600"></i>
                                </div>
                                Geographic & Career
                            </h2>
                            <p class="text-slate-600 ml-13">Location and employment details</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Region / Location *</label>
                                <select name="region" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" required>
                                    <option value="">Select Region...</option>
                                    <option value="Arusha" {{ old('region') === 'Arusha' ? 'selected' : '' }}>Arusha</option>
                                    <option value="Dar es Salaam" {{ old('region') === 'Dar es Salaam' ? 'selected' : '' }}>Dar es Salaam</option>
                                    <option value="Dodoma" {{ old('region') === 'Dodoma' ? 'selected' : '' }}>Dodoma</option>
                                    <option value="Geita" {{ old('region') === 'Geita' ? 'selected' : '' }}>Geita</option>
                                    <option value="Iringa" {{ old('region') === 'Iringa' ? 'selected' : '' }}>Iringa</option>
                                    <option value="Kagera" {{ old('region') === 'Kagera' ? 'selected' : '' }}>Kagera</option>
                                    <option value="Katavi" {{ old('region') === 'Katavi' ? 'selected' : '' }}>Katavi</option>
                                    <option value="Kigoma" {{ old('region') === 'Kigoma' ? 'selected' : '' }}>Kigoma</option>
                                    <option value="Kilimanjaro" {{ old('region') === 'Kilimanjaro' ? 'selected' : '' }}>Kilimanjaro</option>
                                    <option value="Lindi" {{ old('region') === 'Lindi' ? 'selected' : '' }}>Lindi</option>
                                    <option value="Manyara" {{ old('region') === 'Manyara' ? 'selected' : '' }}>Manyara</option>
                                    <option value="Mara" {{ old('region') === 'Mara' ? 'selected' : '' }}>Mara</option>
                                    <option value="Mbeya" {{ old('region') === 'Mbeya' ? 'selected' : '' }}>Mbeya</option>
                                    <option value="Morogoro" {{ old('region') === 'Morogoro' ? 'selected' : '' }}>Morogoro</option>
                                    <option value="Mtwara" {{ old('region') === 'Mtwara' ? 'selected' : '' }}>Mtwara</option>
                                    <option value="Mwanza" {{ old('region') === 'Mwanza' ? 'selected' : '' }}>Mwanza</option>
                                    <option value="Njombe" {{ old('region') === 'Njombe' ? 'selected' : '' }}>Njombe</option>
                                    <option value="Pwani" {{ old('region') === 'Pwani' ? 'selected' : '' }}>Pwani</option>
                                    <option value="Rukwa" {{ old('region') === 'Rukwa' ? 'selected' : '' }}>Rukwa</option>
                                    <option value="Ruvuma" {{ old('region') === 'Ruvuma' ? 'selected' : '' }}>Ruvuma</option>
                                    <option value="Shinyanga" {{ old('region') === 'Shinyanga' ? 'selected' : '' }}>Shinyanga</option>
                                    <option value="Simiyu" {{ old('region') === 'Simiyu' ? 'selected' : '' }}>Simiyu</option>
                                    <option value="Singida" {{ old('region') === 'Singida' ? 'selected' : '' }}>Singida</option>
                                    <option value="Songwe" {{ old('region') === 'Songwe' ? 'selected' : '' }}>Songwe</option>
                                    <option value="Tabora" {{ old('region') === 'Tabora' ? 'selected' : '' }}>Tabora</option>
                                    <option value="Tanga" {{ old('region') === 'Tanga' ? 'selected' : '' }}>Tanga</option>
                                    <option value="Kaskazini Unguja" {{ old('region') === 'Kaskazini Unguja' ? 'selected' : '' }}>Kaskazini Unguja</option>
                                    <option value="Kusini Unguja" {{ old('region') === 'Kusini Unguja' ? 'selected' : '' }}>Kusini Unguja</option>
                                    <option value="Mjini Magharibi" {{ old('region') === 'Mjini Magharibi' ? 'selected' : '' }}>Mjini Magharibi</option>
                                    <option value="Kaskazini Pemba" {{ old('region') === 'Kaskazini Pemba' ? 'selected' : '' }}>Kaskazini Pemba</option>
                                    <option value="Kusini Pemba" {{ old('region') === 'Kusini Pemba' ? 'selected' : '' }}>Kusini Pemba</option>
                                </select>
                                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                                <input type="hidden" name="detected_region" id="detected_region" value="{{ old('detected_region') }}">
                                <input type="hidden" name="location_source" id="location_source" value="{{ old('location_source') }}">
                                <input type="hidden" name="location_accuracy" id="location_accuracy" value="{{ old('location_accuracy') }}">
                                <p id="locationStatus" class="text-xs text-slate-500 mt-2">Location detection is enabled to verify the region you selected.</p>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Employment Status *</label>
                                <select name="employment_status" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" required>
                                    <option value="">Select...</option>
                                    <option value="employed" {{ old('employment_status') === 'employed' ? 'selected' : '' }}>Employed</option>
                                    <option value="self_employed" {{ old('employment_status') === 'self_employed' ? 'selected' : '' }}>Self Employed</option>
                                    <option value="unemployed" {{ old('employment_status') === 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                                    <option value="freelancer" {{ old('employment_status') === 'freelancer' ? 'selected' : '' }}>Freelancer</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Current Job Title (if employed)</label>
                                <input type="text" name="job_title" value="{{ old('job_title') }}" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Expected Salary Range (TZS)</label>
                                <input type="number" name="expected_salary" value="{{ old('expected_salary') }}" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" placeholder="e.g. 2000000">
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Years of Experience</label>
                                <input type="number" name="experience_years" value="{{ old('experience_years') }}" min="0" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">LinkedIn Profile</label>
                                <input type="url" name="linkedin" value="{{ old('linkedin') }}" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" placeholder="https://linkedin.com/in/...">
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Skills & Certifications -->
                    <div class="form-section" data-step="3">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-star text-amber-600"></i>
                                </div>
                                Skills & Languages
                            </h2>
                            <p class="text-slate-600 ml-13">Your expertise and qualifications</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Key Skills (comma-separated)</label>
                                <textarea name="skills" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" placeholder="e.g. Python, Data Analysis, Project Management">{{ old('skills') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Languages Spoken</label>
                                <textarea name="languages" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" placeholder="e.g. Swahili, English, French">{{ old('languages') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Certifications</label>
                                <textarea name="certifications" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" placeholder="e.g. AWS Certified, PMP, CPA">{{ old('certifications') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Job Preferences</label>
                                <textarea name="job_preferences" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" placeholder="e.g. Remote work, Full-time, Relocatable">{{ old('job_preferences') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Security -->
                    <div class="form-section" data-step="4">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-lock text-red-600"></i>
                                </div>
                                Security & Agreement
                            </h2>
                            <p class="text-slate-600 ml-13">Secure your account</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Password *</label>
                                <input type="password" name="password" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" required>
                                <p class="text-xs text-slate-500 mt-2">At least 8 characters, with uppercase and numbers</p>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-3">Confirm Password *</label>
                                <input type="password" name="password_confirmation" class="w-full border border-slate-300 rounded-lg px-4 py-3 input-field focus:ring-2 focus:ring-indigo-500" required>
                            </div>
                        </div>
                        <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="terms" required class="mt-1 w-5 h-5 accent-indigo-600">
                                <span class="text-sm text-slate-700">I agree to the <a href="{{ route('terms') }}" target="_blank" class="text-indigo-600 font-semibold hover:underline">Terms & Conditions</a> and <a href="{{ route('privacy') }}" target="_blank" class="text-indigo-600 font-semibold hover:underline">Privacy Policy</a></span>
                            </label>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex gap-3 items-center justify-between pt-8 border-t border-slate-200">
                        <button type="button" @click="previousStep()" class="px-6 py-3 border border-slate-300 rounded-lg text-slate-700 font-semibold hover:bg-slate-50 transition" :disabled="currentStep === 0" :class="{ 'opacity-50 cursor-not-allowed': currentStep === 0 }">
                            <i class="fas fa-chevron-left mr-2"></i> Previous
                        </button>
                        
                        <div class="flex gap-2">
                            <button type="button" id="previewBtn" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-lg font-semibold hover:bg-slate-200 transition">
                                <i class="fas fa-eye mr-2"></i> Preview
                            </button>
                            <button type="button" @click="nextStep()" x-show="currentStep < steps.length - 1" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition btn-next">
                                Next <i class="fas fa-chevron-right ml-2"></i>
                            </button>
                            <button type="submit" @show="currentStep === steps.length - 1" id="submitBtn" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-lg font-semibold hover:shadow-lg transition" :disabled="currentStep !== steps.length - 1">
                                <i class="fas fa-check mr-2"></i> Complete Registration
                            </button>
                        </div>
                        
                        <a href="{{ route('login') }}" class="px-6 py-3 border-2 border-indigo-600 text-indigo-600 rounded-lg font-semibold hover:bg-indigo-50 transition text-center">
                            Already have account?
                        </a>
                    </div>
                </form>
                
                <!-- Preview Modal -->
                <div id="previewModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50" data-otp-send-url="{{ route('otp.send') }}" data-otp-verify-url="{{ route('otp.verify') }}" data-csrf-token="{{ csrf_token() }}">
                    <div class="bg-white rounded-lg p-8 max-w-2xl w-full max-h-96 overflow-y-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-slate-900">Preview Your Profile</h3>
                            <button id="closePreview" class="text-slate-500 hover:text-slate-700 text-2xl">&times;</button>
                        </div>
                        <div id="previewContent" class="text-sm text-slate-700 mb-6"></div>
                        <div class="flex justify-end gap-3">
                            <button id="closePreview2" class="px-6 py-2 rounded border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50">Cancel</button>
                            <button id="confirmSubmit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700">Confirm & Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Registration Logic -->
    <script>
        const otpSendUrl = '{{ route('otp.send') }}';
        const otpVerifyUrl = '{{ route('otp.verify') }}';
        const csrfToken = '{{ csrf_token() }}';
        let otpVerified = false;

        function setOtpMessage(message, isError = false) {
            const otpResult = document.getElementById('otpResult');
            const otpNotice = document.getElementById('otpNotice');
            if (!otpResult || !otpNotice) return;

            otpResult.textContent = message;
            otpResult.className = 'text-sm mt-3 font-medium ' + (isError ? 'text-red-600' : 'text-emerald-600');
            otpNotice.classList.toggle('hidden', !message || isError);
            otpNotice.textContent = isError ? '' : 'OTP sent (dev mode shows code).';
        }

        async function postOtpRequest(url, payload) {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: new URLSearchParams(payload),
            });

            const data = await response.json().catch(() => ({}));
            return { ok: response.ok, status: response.status, data };
        }

        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const verifyOtpBtn = document.getElementById('verifyOtpBtn');
        const otpInput = document.getElementById('otpInput');
        const phoneInput = document.getElementById('phoneInput');

        if (sendOtpBtn) {
            sendOtpBtn.addEventListener('click', async function () {
                const phone = phoneInput ? phoneInput.value.trim() : '';

                if (!phone) {
                    setOtpMessage('Please enter your phone number before requesting OTP.', true);
                    phoneInput?.focus();
                    return;
                }

                this.disabled = true;
                this.textContent = 'Sending...';
                setOtpMessage('Sending OTP...', false);

                const result = await postOtpRequest(otpSendUrl, { phone });

                if (result.ok && result.data.status === 'ok') {
                    otpVerified = false;
                    if (otpInput) otpInput.disabled = false;
                    if (verifyOtpBtn) verifyOtpBtn.disabled = false;
                    const devHint = result.data.dev ? ` Dev code: ${result.data.code}` : '';
                    setOtpMessage(`OTP request sent successfully.${devHint} Enter the code and verify it to enable registration.`, false);
                } else {
                    const message = result.data?.message || 'Unable to send OTP. Please try again.';
                    setOtpMessage(message, true);
                    if (otpInput) otpInput.disabled = true;
                    if (verifyOtpBtn) verifyOtpBtn.disabled = true;
                }

                this.disabled = false;
                this.textContent = 'Send OTP';
            });
        }

        if (verifyOtpBtn) {
            verifyOtpBtn.addEventListener('click', async function () {
                const phone = phoneInput ? phoneInput.value.trim() : '';
                const code = otpInput ? otpInput.value.trim() : '';

                if (!phone) {
                    setOtpMessage('Enter your phone number before verifying OTP.', true);
                    phoneInput?.focus();
                    return;
                }

                if (!code) {
                    setOtpMessage('Enter the 6-digit OTP code to verify your phone number.', true);
                    otpInput?.focus();
                    return;
                }

                this.disabled = true;
                this.textContent = 'Verifying...';
                setOtpMessage('Verifying OTP...', false);

                const result = await postOtpRequest(otpVerifyUrl, { phone, code });

                if (result.ok && result.data.status === 'ok') {
                    otpVerified = true;
                    setOtpMessage('Phone number verified successfully. You can now complete registration.', false);
                    if (otpInput) otpInput.disabled = true;
                    this.disabled = true;
                    this.textContent = 'Verified';
                } else {
                    otpVerified = false;
                    const message = result.data?.message || 'OTP verification failed. Please try again.';
                    setOtpMessage(message, true);
                }

                this.disabled = false;
                if (this.textContent === 'Verified') {
                    this.disabled = true;
                } else {
                    this.textContent = 'Verify';
                }
            });
        }

        const registrationForm = document.getElementById('regForm');
        if (registrationForm) {
            registrationForm.addEventListener('submit', function (event) {
                if (!otpVerified) {
                    event.preventDefault();
                    setOtpMessage('Please verify your phone number with OTP before completing registration.', true);
                    document.getElementById('phoneInput')?.focus();
                    return false;
                }
            });
        }

        function registrationForm() {
            return {
                currentStep: 0,
                completionTick: 0,
                steps: [
                    { label: 'Step 1', title: 'Personal Info', icon: 'user' },
                    { label: 'Step 2', title: 'Academic Info', icon: 'book' },
                    { label: 'Step 3', title: 'Career Info', icon: 'briefcase' },
                    { label: 'Step 4', title: 'Skills', icon: 'star' },
                    { label: 'Step 5', title: 'Security', icon: 'lock' }
                ],
                nextStep() {
                    if (this.currentStep < this.steps.length - 1) {
                        document.querySelector(`.form-section[data-step="${this.currentStep}"]`).classList.remove('active');
                        this.currentStep++;
                        document.querySelector(`.form-section[data-step="${this.currentStep}"]`).classList.add('active');
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },
                progressPercent() {
                    this.completionTick;
                    return Math.round(((this.currentStep + 1) / this.steps.length) * 100);
                },
                progressLabel() {
                    this.completionTick;
                    return `Progress: ${this.progressPercent()}% complete`;
                },
                completionPercent() {
                    this.completionTick;
                    const form = document.getElementById('regForm');
                    if (!form) return 0;
                    const fields = [
                        form.first_name,
                        form.last_name,
                        form.email,
                        form.phone,
                        form.national_id,
                        form.gender,
                        form.university,
                        form.course,
                        form.degree,
                        form.graduation_date,
                        form.graduation_year,
                        form.gpa,
                        form.region,
                        form.employment_status,
                        form.resume
                    ];
                    const total = fields.length;
                    const filled = fields.reduce((count, field) => {
                        if (!field) return count;
                        if (field.type === 'file') {
                            return count + (field.files.length ? 1 : 0);
                        }
                        return count + (field.value && field.value.trim() !== '' ? 1 : 0);
                    }, 0);
                    return Math.round((filled / total) * 100);
                },
                completionLabel() {
                    return `Profile completeness: ${this.completionPercent()}%`;
                },
                refreshCompletion() {
                    this.completionTick++;
                },
                previousStep() {
                    if (this.currentStep > 0) {
                        document.querySelector(`.form-section[data-step="${this.currentStep}"]`).classList.remove('active');
                        this.currentStep--;
                        document.querySelector(`.form-section[data-step="${this.currentStep}"]`).classList.add('active');
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            }
        }

        // Resume validation
        const allowedExtensions = ['pdf', 'doc', 'docx'];
        const resumeInput = document.getElementById('resume');
        const resumeError = document.getElementById('resumeError');
        resumeInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) {
                resumeError.classList.add('hidden');
                resumeError.textContent = '';
                return;
            }
            const extension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(extension)) {
                resumeError.classList.remove('hidden');
                resumeError.textContent = 'Invalid file type. Only PDF, DOC, and DOCX are allowed.';
                this.value = '';
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                resumeError.classList.remove('hidden');
                resumeError.textContent = 'File is too large. Maximum size is 5MB.';
                this.value = '';
                return;
            }
            resumeError.classList.add('hidden');
            resumeError.textContent = '';
        });

        function setLocationStatus(message, isError = false) {
            const status = document.getElementById('locationStatus');
            if (!status) return;
            status.textContent = message;
            status.classList.toggle('text-red-600', isError);
            status.classList.toggle('text-slate-500', !isError);
        }

        function estimateRegion(lat, lng) {
            if (lat >= 6.4 && lat <= 7.2 && lng >= 39.0 && lng <= 39.5) {
                return 'Dar es Salaam';
            }
            if (lat >= -3.5 && lat <= -2.0 && lng >= 36.4 && lng <= 37.2) {
                return 'Arusha';
            }
            if (lat >= -6.4 && lat <= -5.6 && lng >= 35.4 && lng <= 36.6) {
                return 'Dodoma';
            }
            if (lat >= -9.0 && lat <= -7.9 && lng >= 32.5 && lng <= 34.0) {
                return 'Mbeya';
            }
            if (lat >= -4.5 && lat <= -2.5 && lng >= 37.0 && lng <= 38.5) {
                return 'Kilimanjaro';
            }
            if (lat >= -7.3 && lat <= -5.5 && lng >= 37.0 && lng <= 38.7) {
                return 'Morogoro';
            }
            if (lat >= -3.5 && lat <= -1.0 && lng >= 31.0 && lng <= 33.0) {
                return 'Mwanza';
            }
            if (lat >= -6.0 && lat <= -4.0 && lng >= 37.5 && lng <= 39.5) {
                return 'Tanga';
            }
            return null;
        }

        function populateLocationFields(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const accuracy = position.coords.accuracy;
            const detectedRegion = estimateRegion(lat, lng);

            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const detectedRegionInput = document.getElementById('detected_region');
            const locationSourceInput = document.getElementById('location_source');
            const locationAccuracyInput = document.getElementById('location_accuracy');

            if (latitudeInput) latitudeInput.value = lat;
            if (longitudeInput) longitudeInput.value = lng;
            if (detectedRegionInput) detectedRegionInput.value = detectedRegion || '';
            if (locationSourceInput) locationSourceInput.value = 'browser';
            if (locationAccuracyInput) locationAccuracyInput.value = `${accuracy} meters`;

            if (detectedRegion) {
                setLocationStatus(`Detected location is ${detectedRegion}. Please make sure your selected region matches.`);
            } else {
                setLocationStatus('Location detected, but the region could not be matched to a known area. Please select the correct region manually.', true);
            }
        }

        function locateUser() {
            if (!navigator.geolocation) {
                setLocationStatus('Geolocation not available in this browser. Please choose your region manually.', true);
                return;
            }

            setLocationStatus('Detecting your location...');
            navigator.geolocation.getCurrentPosition(populateLocationFields, function (error) {
                const message = {
                    1: 'Permission denied. Enable location services if you want automatic region detection.',
                    2: 'Position unavailable. Please select your region manually.',
                    3: 'Location request timed out. Please select your region manually.'
                }[error.code] || 'Unable to detect location. Please select your region manually.';
                setLocationStatus(message, true);
                const locationSourceInput = document.getElementById('location_source');
                if (locationSourceInput) locationSourceInput.value = 'browser_failed';
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            });
        }

        locateUser();

        // Preview Modal Logic
        document.getElementById('previewBtn').addEventListener('click', function() {
            const form = document.getElementById('regForm');
            const formData = new FormData(form);
            const preview = {};
            
            for (let [key, value] of formData.entries()) {
                preview[key] = value;
            }

            const previewHTML = Object.entries(preview)
                .filter(([, val]) => val && val !== '')
                .map(([key, val]) => `<div class="mb-2"><strong>${key.replace(/_/g, ' ')}:</strong> ${val}</div>`)
                .join('');
            
            document.getElementById('previewContent').innerHTML = previewHTML;
            document.getElementById('previewModal').classList.remove('hidden');
            document.getElementById('previewModal').classList.add('flex');
        });

        document.getElementById('closePreview').addEventListener('click', function() {
            document.getElementById('previewModal').classList.add('hidden');
            document.getElementById('previewModal').classList.remove('flex');
        });

        document.getElementById('closePreview2').addEventListener('click', function() {
            document.getElementById('previewModal').classList.add('hidden');
            document.getElementById('previewModal').classList.remove('flex');
        });

        document.getElementById('confirmSubmit').addEventListener('click', function() {
            document.getElementById('regForm').submit();
        });
    </script>
