<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Verification - TARIQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-50 to-indigo-50 min-h-screen">
    <div class="py-12 px-4" x-data="verificationForm()">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="w-24 h-24 bg-gradient-to-br from-indigo-600 to-blue-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl">
                    <i class="fas fa-certificate text-white text-5xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-slate-900 mb-3">Academic Verification</h1>
                <p class="text-slate-600 text-lg max-w-lg mx-auto">
                    Enter your index number to verify your academic credentials with participating universities and institutions.
                </p>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10 border border-slate-100">
                
                <!-- Info Alert -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded-lg mb-8">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-blue-600 text-xl mt-0.5"></i>
                        <div>
                            <h3 class="font-semibold text-blue-900 mb-1">What is Academic Verification?</h3>
                            <p class="text-blue-800 text-sm">We connect with universities and the Ministry of Education to verify your academic records. If systems are temporarily unavailable, your verification will be manually reviewed.</p>
                        </div>
                    </div>
                </div>

                <!-- Verification Form -->
                <form @submit.prevent="submitVerification()" class="space-y-6">
                    @csrf
                    
                    <!-- Index Number Input -->
                    <div>
                        <label class="block text-slate-700 font-semibold mb-3 flex items-center gap-2">
                            <i class="fas fa-id-card text-indigo-600 text-lg"></i>
                            Index Number *
                        </label>
                        <input 
                            type="text" 
                            x-model="form.indexNumber"
                            placeholder="e.g., MAT/2024/001234"
                            class="w-full px-5 py-4 border-2 border-slate-300 rounded-xl focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 text-base transition"
                            required
                        >
                        <p class="text-slate-500 text-sm mt-2">Your university-issued index or student ID number</p>
                    </div>

                    <!-- Registration Number Input (Optional) -->
                    <div>
                        <label class="block text-slate-700 font-semibold mb-3 flex items-center gap-2">
                            <i class="fas fa-address-card text-slate-600 text-lg"></i>
                            Registration Number (Optional)
                        </label>
                        <input 
                            type="text" 
                            x-model="form.registrationNumber"
                            placeholder="e.g., REG/2024/789456"
                            class="w-full px-5 py-4 border-2 border-slate-300 rounded-xl focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 text-base transition"
                        >
                        <p class="text-slate-500 text-sm mt-2">Additional registration or exam number if available</p>
                    </div>

                    <!-- Status Message -->
                    <div v-if="statusMessage" :class="statusClass" class="p-4 rounded-xl flex items-center gap-3">
                        <i :class="statusIcon" class="text-xl"></i>
                        <p x-text="statusMessage" class="font-medium"></p>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        :disabled="loading"
                        class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-bold py-4 rounded-xl hover:shadow-lg transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-lg"
                    >
                        <i :class="loading ? 'fas fa-spinner fa-spin' : 'fas fa-check-circle'" class="text-xl"></i>
                        <span x-text="loading ? 'Verifying...' : 'Verify Academic Records'"></span>
                    </button>

                    <!-- Info Section -->
                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                        <h3 class="font-semibold text-slate-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-amber-600"></i> What happens next?
                        </h3>
                        <ul class="space-y-3 text-slate-700">
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-indigo-600 font-bold text-sm">1</span>
                                </div>
                                <span><strong>Instant Verification:</strong> If your university is connected to TARIQ, we verify instantly.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-indigo-600 font-bold text-sm">2</span>
                                </div>
                                <span><strong>Pending Review:</strong> If systems are offline, an administrator will verify manually within 24 hours.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-indigo-600 font-bold text-sm">3</span>
                                </div>
                                <span><strong>Complete Profile:</strong> Once verified, finish your graduate profile and start exploring opportunities.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Alternative Path -->
                    <div class="text-center pt-4 border-t border-slate-200">
                        <p class="text-slate-600 mb-4">
                            Don't have an index number or need help?
                        </p>
                        <a href="{{ route('graduate.register') }}" class="text-indigo-600 font-semibold hover:text-indigo-700 flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            Continue to Full Registration
                        </a>
                    </div>
                </form>

            </div>

            <!-- Footer Info -->
            <div class="mt-8 text-center text-slate-600 text-sm">
                <p>Verification data is encrypted and securely stored. We never share your information without your consent.</p>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/verify-academic.js') }}" defer></script>
</body>
</html>
