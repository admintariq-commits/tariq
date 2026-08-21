<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TARIQ - Graduate Employment Intelligence</title>
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
        .hero-shadow { box-shadow: 0 30px 80px rgba(12, 16, 30, 0.22); overflow: hidden; position: relative; }
        .hero-blob { position: absolute; border-radius: 9999px; filter: blur(72px); opacity: 0.36; transform: translateZ(0); }
        .hero-title { font-family: 'Merriweather', serif; font-weight:900; letter-spacing: -0.01em; line-height:0.96; }
        .hero-subtle { color: rgba(255,255,255,0.9); }
        .btn-primary { background: white; color: #5b21b6; padding: 0.65rem 1.25rem; border-radius: 9999px; box-shadow: 0 10px 30px rgba(99,102,241,0.12); font-weight:700; }
        .btn-secondary { border: 1px solid rgba(255,255,255,0.24); background: rgba(255,255,255,0.06); color: #fff; padding: 0.6rem 1.1rem; border-radius: 9999px; }
        .feature-card { transition: transform .28s ease, box-shadow .28s ease; border-left-width: 4px; }
        .feature-card:hover { transform: translateY(-6px) scale(1.01); box-shadow: 0 18px 40px rgba(2,6,23,0.12); }
        .feature-spot { display:flex; gap:1rem; align-items:center; min-height:82px; }
        .feature-spot .icon { width:52px; height:52px; flex:0 0 52px; display:flex; align-items:center; justify-content:center; border-radius:14px; font-size:1.1rem; }
        .cta-primary { background: linear-gradient(90deg,#fff 0%, #f8fafc 100%); color:#5b21b6; }
        .hero-sub { color: rgba(255,255,255,0.9); }
        .feature-spot .title { font-weight:700; font-size:0.95rem; line-height:1.25; }
        .feature-spot .description { color:rgba(255,255,255,0.78); font-size:0.82rem; line-height:1.35; margin-top:0.25rem; }
        .tz-brand { display:inline-flex; align-items:center; gap:0.4rem; border:1px solid rgba(91,33,182,0.14); background:#faf5ff; color:#5b21b6; border-radius:9999px; padding:0.3rem 0.65rem; font-size:0.68rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; }
        .muted { color: rgba(15,23,42,0.65); }
        @media (max-width: 640px) {
            .hero-blob { display: none; }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-slate-50 text-slate-900">
    <nav class="bg-white shadow-md fixed w-full z-20 top-0">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-sm sm:text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-base sm:text-lg font-semibold text-slate-900 truncate">TARIQ</div>
                        <div class="text-xs uppercase tracking-[0.24em] text-slate-500 truncate hidden sm:block">Hub</div>
                    </div>
                    <span class="tz-brand" title="Tanzania Graduate Intelligence"><span class="text-base leading-none">🇹🇿</span><span class="hidden sm:inline">Tanzania</span></span>
                </div>
                <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
                    <a href="{{ route('login') }}" class="text-xs sm:text-sm text-slate-700 hover:text-purple-600 transition whitespace-nowrap">Login</a>
                    <a href="{{ route('graduate.register') }}" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-3 sm:px-5 py-1.5 sm:py-2 text-xs sm:text-sm rounded-full font-semibold hover:shadow-lg transition whitespace-nowrap">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-24">
        <section class="relative overflow-hidden hero-gradient text-white">
            <div class="hero-blob bg-fuchsia-400/40 h-56 w-56 top-10 left-6"></div>
            <div class="hero-blob bg-cyan-400/35 h-72 w-72 bottom-0 right-8"></div>
            <div class="hero-blob bg-violet-500/35 h-96 w-96 top-24 right-1/2 translate-x-1/2"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative py-10 sm:py-14 md:py-16 text-center hero-shadow rounded-b-3xl">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-5 py-2 text-sm uppercase tracking-[0.2em] text-white/95 mb-5">
                    <span class="text-base">🇹🇿</span> Tanzania Graduate Intelligence
                </span>
                <h1 class="text-3xl sm:text-4xl md:text-5xl hero-title leading-tight mb-4">Welcome to <span class="text-pink-200">TARIQ</span> — Tanzania Graduate Intelligence</h1>
                <p class="mx-auto max-w-3xl text-sm sm:text-base hero-subtle mb-6">A practical platform for Tanzanian graduates, employers, and government teams to understand opportunities, skills demand, and regional workforce trends.</p>
                <div class="mx-auto max-w-4xl grid gap-3 sm:grid-cols-3 mb-6 text-left">
                    <div class="rounded-lg bg-white/6 border border-white/10 p-3 backdrop-blur-xl feature-spot">
                        <div class="icon bg-white/10 text-purple-200">
                            <i class="fas fa-id-badge text-white"></i>
                        </div>
                        <div>
                            <div class="feature-spot title text-white">Verified graduate profiles</div>
                            <div class="feature-spot description">Secure verification of credentials and experience</div>
                        </div>
                    </div>
                    <div class="rounded-lg bg-white/6 border border-white/10 p-3 backdrop-blur-xl feature-spot">
                        <div class="icon bg-white/10 text-pink-200">
                            <i class="fas fa-bullseye text-white"></i>
                        </div>
                        <div>
                            <div class="feature-spot title text-white">Live employer demand insights</div>
                            <div class="feature-spot description">Real-time trends for hiring and skills gaps</div>
                        </div>
                    </div>
                    <div class="rounded-lg bg-white/6 border border-white/10 p-3 backdrop-blur-xl feature-spot">
                        <div class="icon bg-white/10 text-sky-200">
                            <i class="fas fa-map text-white"></i>
                        </div>
                        <div>
                            <div class="feature-spot title text-white">Trusted regional outcomes</div>
                            <div class="feature-spot description">Insights by region to guide local policy</div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('graduate.register') }}" class="btn-primary inline-flex items-center gap-2">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary inline-flex items-center gap-2">Login</a>
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="border-t border-slate-200/6 my-8"></div>
        </div>

        <section class="relative -mt-8 px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                <div class="mb-6 text-center">
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-500 mb-2">What TARIQ delivers</p>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Powerful intelligence for graduates and employers</h2>
                </div>
                <div class="grid gap-3 md:grid-cols-3">
                <div class="feature-card rounded-lg bg-white border border-slate-200 p-5 shadow-sm">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center mb-2">
                        <i class="fas fa-chart-line text-lg"></i>
                    </div>
                    <h2 class="text-lg font-bold mb-1">Track Employability</h2>
                    <p class="text-sm text-slate-600">Measure graduate readiness across skills, GPA, experience, and geography.</p>
                </div>
                <div class="feature-card rounded-lg bg-white border border-slate-200 p-5 shadow-sm">
                    <div class="w-10 h-10 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center mb-2">
                        <i class="fas fa-briefcase text-lg"></i>
                    </div>
                    <h2 class="text-lg font-bold mb-1">Smart Job Matching</h2>
                    <p class="text-sm text-slate-600">Connect graduates with the best local and national opportunities.</p>
                </div>
                <div class="feature-card rounded-lg bg-white border border-slate-200 p-5 shadow-sm">
                    <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center mb-2">
                        <i class="fas fa-map-marked-alt text-lg"></i>
                    </div>
                    <h2 class="text-lg font-bold mb-1">Regional Analysis</h2>
                    <p class="text-sm text-slate-600">Visualize graduate supply, employer demand, and regional gaps.</p>
                </div>
            </div>
        </section>

        <section class="py-12 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-6 text-center">
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-500 mb-2">Milestones</p>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Trusted progress across the graduate journey</h2>
                </div>
                <div class="grid gap-4 md:grid-cols-4">
                    <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm">
                        <div class="text-2xl font-extrabold text-slate-900">1250+</div>
                        <p class="mt-2 text-sm text-slate-500">Registered Graduates</p>
                    </div>
                    <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm">
                        <div class="text-2xl font-extrabold text-slate-900">85%</div>
                        <p class="mt-2 text-sm text-slate-500">Placement Rate</p>
                    </div>
                    <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm">
                        <div class="text-2xl font-extrabold text-slate-900">30+</div>
                        <p class="mt-2 text-sm text-slate-500">Growing Employer Network</p>
                    </div>
                    <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm">
                        <div class="text-2xl font-extrabold text-slate-900">31</div>
                        <p class="mt-2 text-sm text-slate-500">Regions Covered</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-12">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="rounded-lg bg-gradient-to-r from-purple-700 via-fuchsia-600 to-pink-500 p-6 text-white shadow-lg shadow-purple-500/20">
                    <div class="grid gap-4 lg:grid-cols-2 items-center">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-white/80 mb-2">Join the graduate intelligence movement</p>
                            <h2 class="text-base md:text-lg font-bold mb-2">One platform for jobs, data, and national impact.</h2>
                            <p class="text-sm text-white/90 mb-4">TARIQ helps graduates, employers, and policymakers make faster, smarter decisions with trusted data and career matching.</p>
                            <a href="{{ route('graduate.register') }}" class="inline-flex items-center justify-center rounded-full bg-white text-purple-700 px-5 py-1.5 text-sm font-semibold shadow-md shadow-white/20 hover:bg-white/90 transition">Start Your Profile</a>
                        </div>
                        <div class="grid gap-2 sm:grid-cols-2">
                            <div class="rounded-lg bg-white/10 p-3">
                                <p class="text-sm font-semibold">Verified graduate skills</p>
                            </div>
                            <div class="rounded-lg bg-white/10 p-3">
                                <p class="text-sm font-semibold">Employer-ready profiles</p>
                            </div>
                            <div class="rounded-lg bg-white/10 p-3">
                                <p class="text-sm font-semibold">Regional workforce trends</p>
                            </div>
                            <div class="rounded-lg bg-white/10 p-3">
                                <p class="text-sm font-semibold">Policy-ready intelligence</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-slate-900 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-3">
                <div class="text-center md:text-left">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-500 mb-1">TARIQ</p>
                    <p class="text-xs">Tanzania Alumni & Graduate Intelligence System</p>
                    <p class="text-xs text-slate-400 mt-2">© 2026 TARIQ</p>
                </div>
                <div class="text-center md:text-right">
                    <a href="{{ route('terms') }}" class="text-slate-300 hover:text-white text-sm mr-4">Terms</a>
                    <a href="{{ route('privacy') }}" class="text-slate-300 hover:text-white text-sm">Privacy</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>