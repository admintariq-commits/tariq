<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TARIQ - Graduate Employment Intelligence System</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .gradient-text { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -12px rgba(0,0,0,0.2); }
        .sidebar-transition { transition: transform 0.3s ease; }
        .dark { background-color: #1a1a2e; color: #e0e0e0; }
        .dark .bg-white { background-color: #1f2937; }
        .dark .bg-gray-100 { background-color: #111827; }
        .dark .text-gray-700 { color: #d1d5db; }
        .dark .border-gray-200 { border-color: #374151; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900" x-data="{ sidebarOpen: true, darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">

    <!-- ========== TOP NAVBAR ========== -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <div class="flex items-center space-x-4">
                <!-- Sidebar Toggle Button -->
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold gradient-text">TARIQ</span>
                    <span class="text-xs bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300 px-2 py-1 rounded-full">v4.0</span>
                </div>
            </div>

            <!-- Right Side Navbar -->
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white">
                    <i class="fas text-xl" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                </button>

                <!-- Notifications Bell -->
                @auth
                    @php
                        $notifications = \App\Models\Notification::where('user_id', auth()->id())
                            ->where(function ($query) {
                                $query->whereNull('expires_at')
                                    ->orWhere('expires_at', '>', now());
                            })
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                        $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                            ->where('is_read', false)
                            ->where(function ($query) {
                                $query->whereNull('expires_at')
                                    ->orWhere('expires_at', '>', now());
                            })
                            ->count();
                    @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white relative">
                            <i class="fas fa-bell text-xl"></i>
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $unreadCount }}</span>
                            @endif
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 font-bold">Notifications</div>
                            @if($notifications->isEmpty())
                                <div class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">No new notifications.</div>
                            @else
                                @foreach($notifications as $notification)
                                    <a href="{{ $notification->action_url ?? '#' }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                        {!! $notification->title !!}<br>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($notification->message, 60) }}</span>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @else
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white relative">
                            <i class="fas fa-bell text-xl"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 font-bold">Notifications</div>
                            <div class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">Login to see your notifications.</div>
                        </div>
                    </div>
                @endauth

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span class="hidden md:inline">{{ Auth::user()->name ?? 'Guest' }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-2 z-50">
                        @if(Auth::check() && Auth::user()->role && Auth::user()->role->name === 'admin')
                            <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><i class="fas fa-user mr-2"></i>Profile</a>
                            <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><i class="fas fa-cog mr-2"></i>Settings</a>
                        @elseif(Auth::check() && Auth::user()->role && Auth::user()->role->name === 'ministry')
                            <a href="{{ route('ministry.dashboard') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><i class="fas fa-landmark mr-2"></i>Ministry Dashboard</a>
                        @elseif(Auth::check() && Auth::user()->role && Auth::user()->role->name === 'graduate')
                            <a href="{{ route('graduate.profile') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><i class="fas fa-user mr-2"></i>Profile</a>
                        @else
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Home</a>
                        @endif
                        <hr class="my-1 border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700"><i class="fas fa-sign-out-alt mr-2"></i>Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- ========== SIDEBAR ========== -->
    <aside class="fixed top-16 left-0 z-40 w-64 h-screen transition-transform sidebar-transition bg-white dark:bg-gray-800 shadow-lg" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-full px-3 py-4 overflow-y-auto">
            <ul class="space-y-2 font-medium">
                @if(Auth::check() && Auth::user()->role && Auth::user()->role->name === 'admin')
                    <!-- Admin Menu -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-tachometer-alt w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.graduates.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-users w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                            <span class="ml-3">Graduates</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.graduates.create') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-user-plus w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                            <span class="ml-3">Add Graduate</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.alerts.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-bell w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                            <span class="ml-3">Alerts</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.heatmap') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-map w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                            <span class="ml-3">Heatmap</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.universities.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-school w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                            <span class="ml-3">Universities</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.courses.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-book w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                            <span class="ml-3">Courses</span>
                        </a>
                    </li>
                @elseif(Auth::check() && Auth::user()->role && Auth::user()->role->name === 'ministry')
                    <!-- Ministry Menu -->
                    <li>
                        <a href="{{ route('ministry.dashboard') }}" class="sidebar-item flex items-center p-3 text-gray-900 dark:text-white hover:bg-indigo-50 dark:hover:bg-gray-700 group">
                            <i class="fas fa-landmark w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
                            <span class="ml-3 text-sm font-medium">Ministry Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ministry.analytics.dashboard') }}" class="sidebar-item flex items-center p-3 text-gray-900 dark:text-white hover:bg-indigo-50 dark:hover:bg-gray-700 group">
                            <i class="fas fa-chart-line w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
                            <span class="ml-3 text-sm font-medium">Employment Intelligence</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ministry.alerts.index') }}" class="sidebar-item flex items-center p-3 text-gray-900 dark:text-white hover:bg-indigo-50 dark:hover:bg-gray-700 group">
                            <i class="fas fa-bell w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
                            <span class="ml-3 text-sm font-medium">Ministry Alerts</span>
                        </a>
                    </li>
                @endif

                @if(Auth::check() && Auth::user()->role && Auth::user()->role->name === 'graduate')
                    <!-- Graduate Menu -->
                    <li>
                        <a href="{{ route('graduate.profile') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-user w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                            <span class="ml-3">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('graduate.job-matches') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-briefcase w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                            <span class="ml-3">Job Matches</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('graduate.applications') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <i class="fas fa-file-alt w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                            <span class="ml-3">My Applications</span>
                        </a>
                    </li>
                @endif
            </ul>

<div class="mt-4">
                <a href="{{ route('about') }}" class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i class="fas fa-info-circle w-5 h-5 text-gray-500 dark:text-gray-400"></i>
                    <span class="ml-3">About TARIQ</span>
                </a>
            </div>

            <!-- Sidebar Footer -->
            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center p-2 text-gray-500 dark:text-gray-400">
                    <i class="fas fa-database w-5 h-5"></i>
                    <span class="ml-3 text-xs">TARIQ v4.0 | Tanzania</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- ========== MAIN CONTENT ========== -->
    <main class="pt-20 pb-8 transition-all duration-300" :class="sidebarOpen ? 'pl-64' : 'pl-0'">
        <div class="px-6 max-w-7xl mx-auto">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="flash-message bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900 dark:to-emerald-900 border-l-4 border-green-500 text-green-700 dark:text-green-100 p-5 rounded-lg mb-6 flex justify-between items-center shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-green-600 dark:text-green-400"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="flash-close text-green-700 dark:text-green-100 hover:text-green-900 dark:hover:text-green-200 text-xl leading-none">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div class="flash-message bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900 dark:to-pink-900 border-l-4 border-red-500 text-red-700 dark:text-red-100 p-5 rounded-lg mb-6 flex justify-between items-center shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-red-600 dark:text-red-400"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="flash-close text-red-700 dark:text-red-100 hover:text-red-900 dark:hover:text-red-200 text-xl leading-none">&times;</button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="{{ asset('js/tariq.js') }}" defer></script>
</body>
</html>
