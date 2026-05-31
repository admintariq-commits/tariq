<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error - TARIQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-red-50 to-red-100">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="text-center max-w-lg">
            <div class="mb-8">
                <i class="fas fa-exclamation-triangle text-6xl text-red-500 mb-4"></i>
            </div>
            <h1 class="text-5xl font-bold text-slate-900 mb-2">500</h1>
            <h2 class="text-2xl font-semibold text-slate-700 mb-4">Server Error</h2>
            <p class="text-slate-600 mb-8">Something went wrong on our end. Our team has been notified and is working to fix it. Please try again later.</p>
            <div class="flex gap-3 justify-center">
                <a href="{{ route('home') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition">
                    <i class="fas fa-home mr-2"></i> Back to Home
                </a>
                <a href="{{ route('login') }}" class="border-2 border-red-600 text-red-600 px-6 py-2 rounded-lg font-semibold hover:bg-red-50 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
