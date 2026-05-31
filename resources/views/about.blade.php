@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-8">
    <h1 class="text-3xl font-bold mb-4">TARIQ — Tanzania Alumni & Regional Intelligence Quartile</h1>
    <p class="text-sm text-gray-600 mb-6">Version 4.0 | National Graduate Employment Intelligence Platform</p>

    <!-- What is TARIQ -->
    <section class="mb-8 p-6 bg-blue-50 dark:bg-blue-900 rounded-lg">
        <h2 class="text-2xl font-semibold mb-3 text-blue-900 dark:text-blue-100">What is TARIQ?</h2>
        <p class="text-gray-700 dark:text-gray-300 mb-3">TARIQ is a **national-scale platform** for graduate tracking and employment intelligence. It monitors, analyzes, and reports on youth unemployment trends across all 31 regions of Tanzania in real-time.</p>
        <p class="text-gray-700 dark:text-gray-300"><strong>Multi-role platform:</strong> Graduates manage their profiles and job applications. Ministries receive automated alerts for intervention. Admins oversee system data and regional analytics.</p>
    </section>

    <!-- TARIQ Acronym -->
    <section class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">What Does TARIQ Stand For?</h2>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div class="p-4 border-2 border-purple-400 rounded-lg bg-purple-50 dark:bg-purple-900">
                <div class="text-3xl font-bold text-purple-600 dark:text-purple-300">T</div>
                <div class="text-sm text-gray-700 dark:text-gray-300"><strong>Tanzania</strong> — nationwide coverage across 31 regions</div>
            </div>
            <div class="p-4 border-2 border-pink-400 rounded-lg bg-pink-50 dark:bg-pink-900">
                <div class="text-3xl font-bold text-pink-600 dark:text-pink-300">A</div>
                <div class="text-sm text-gray-700 dark:text-gray-300"><strong>Alumni</strong> — tracks graduates from all higher learning institutions</div>
            </div>
            <div class="p-4 border-2 border-green-400 rounded-lg bg-green-50 dark:bg-green-900">
                <div class="text-3xl font-bold text-green-600 dark:text-green-300">R</div>
                <div class="text-sm text-gray-700 dark:text-gray-300"><strong>Regional</strong> — region-specific data and interactive heatmaps</div>
            </div>
            <div class="p-4 border-2 border-orange-400 rounded-lg bg-orange-50 dark:bg-orange-900">
                <div class="text-3xl font-bold text-orange-600 dark:text-orange-300">I</div>
                <div class="text-sm text-gray-700 dark:text-gray-300"><strong>Intelligence</strong> — AI analytics, predictive alerts, and smart job matching</div>
            </div>
            <div class="p-4 border-2 border-red-400 rounded-lg bg-red-50 dark:bg-red-900">
                <div class="text-3xl font-bold text-red-600 dark:text-red-300">Q</div>
                <div class="text-sm text-gray-700 dark:text-gray-300"><strong>Quartile</strong> — data categorized into four performance bands</div>
            </div>
        </div>
    </section>

    <!-- Key Features (Front & Center) -->
    <section class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">🎯 Platform Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 border-l-4 border-blue-500 bg-gray-50 dark:bg-gray-800 rounded">
                <h3 class="font-semibold text-blue-600 dark:text-blue-400">📊 Employability Scoring</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">AI-driven score based on GPA, skills, experience, and market demand. Real-time ranking for each graduate.</p>
            </div>
            <div class="p-4 border-l-4 border-green-500 bg-gray-50 dark:bg-gray-800 rounded">
                <h3 class="font-semibold text-green-600 dark:text-green-400">💼 Intelligent Job Matching</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">Matches graduates with suitable opportunities based on skills, location, and employer requirements.</p>
            </div>
            <div class="p-4 border-l-4 border-purple-500 bg-gray-50 dark:bg-gray-800 rounded">
                <h3 class="font-semibold text-purple-600 dark:text-purple-400">🗺️ Regional Heatmap</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">Interactive map showing unemployment hotspots by region. Click regions to drill down into detailed analytics.</p>
            </div>
            <div class="p-4 border-l-4 border-red-500 bg-gray-50 dark:bg-gray-800 rounded">
                <h3 class="font-semibold text-red-600 dark:text-red-400">🚨 Government Alerts</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">Automatic alerts when graduates remain unemployed >8 months. Triggers intervention protocols for Ministry.</p>
            </div>
            <div class="p-4 border-l-4 border-orange-500 bg-gray-50 dark:bg-gray-800 rounded">
                <h3 class="font-semibold text-orange-600 dark:text-orange-400">📈 Analytics Dashboard</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">Real-time statistics, trend analysis, and predictive insights. Custom reports for policy makers.</p>
            </div>
            <div class="p-4 border-l-4 border-indigo-500 bg-gray-50 dark:bg-gray-800 rounded">
                <h3 class="font-semibold text-indigo-600 dark:text-indigo-400">🔑 Multi-role Access</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">Separate secure portals for Graduates, Admins, and Ministry officials. Role-based data visibility.</p>
            </div>
        </div>
    </section>

    <!-- Security & Privacy -->
    <section class="mb-8 p-6 bg-red-50 dark:bg-red-900 rounded-lg border-2 border-red-300 dark:border-red-700">
        <h2 class="text-2xl font-semibold mb-4 text-red-900 dark:text-red-100">🔒 Security & Privacy</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700 dark:text-gray-300">
            <div>✅ <strong>Role-Based Access Control (RBAC)</strong> — Strict permission model. Graduates cannot access admin data. Ministry sees only anonymized trends.</div>
            <div>✅ <strong>Password Encryption</strong> — bcrypt hashing. No plaintext passwords stored.</div>
            <div>✅ <strong>CSRF Protection</strong> — All forms protected against cross-site request forgery attacks.</div>
            <div>✅ <strong>SQL Injection Prevention</strong> — Laravel ORM prevents direct SQL queries. Parameterized bindings only.</div>
            <div>✅ <strong>Input Validation & Sanitization</strong> — All user inputs validated and escaped before storage.</div>
            <div>✅ <strong>Session Security</strong> — Secure HTTP-only cookies. Session timeout after inactivity.</div>
            <div>✅ <strong>Data Encryption in Transit</strong> — SSL/TLS for all connections. No sensitive data over HTTP.</div>
            <div>✅ <strong>Rate Limiting</strong> — Brute-force protection on login endpoints. Throttled API requests.</div>
            <div>✅ <strong>Audit Logging</strong> — All admin actions logged. Ministry alerts tracked for accountability.</div>
            <div>✅ <strong>GDPR Compliance</strong> — Graduate data protected. Export/delete requests supported.</div>
        </div>
    </section>

    <!-- Platform Capabilities -->
    <section class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">⚙️ Platform Capabilities</h2>
        <ul class="space-y-2 text-gray-700 dark:text-gray-300">
            <li>✓ <strong>Real-time Data Sync</strong> — Graduates update profiles instantly. Ministry sees live unemployment trends.</li>
            <li>✓ <strong>Scalable Architecture</strong> — Designed to handle 100,000+ graduates across 31 regions.</li>
            <li>✓ <strong>API-Ready</strong> — RESTful APIs for third-party integrations (employers, universities, NGOs).</li>
            <li>✓ <strong>Offline Capability</strong> — Critical features work offline; sync when connection restored.</li>
            <li>✓ <strong>Multi-language Support</strong> — English & Kiswahili. Expandable for other languages.</li>
            <li>✓ <strong>Mobile-Responsive</strong> — Full functionality on phones, tablets, and desktops.</li>
        </ul>
    </section>

    <!-- Technical Stack (Bottom) -->
    <section class="mb-8 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
        <h2 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">Technical Stack (For Developers & Partners)</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            <strong>Backend:</strong> Laravel 12.x · PHP 8.2 · MySQL/SQLite<br>
            <strong>Frontend:</strong> Tailwind CSS · Alpine.js · Chart.js<br>
            <strong>Maps & Visualization:</strong> Leaflet (interactive regional heatmap) · GeoJSON<br>
            <strong>Security:</strong> bcrypt, CSRF tokens, CORS, rate limiting
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
            <em>Leaflet is an open-source JavaScript library for interactive maps. On the Heatmap page, clicking a region shows unemployment data, job counts, and trends for that specific area.</em>
        </p>
    </section>

    <footer class="text-sm text-gray-500 dark:text-gray-400 mt-8 text-center border-t pt-4">&copy; {{ date('Y') }} TARIQ System | Empowering Tanzanian Youth Through Data Intelligence | <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Back Home</a></footer>
</div>
@endsection
