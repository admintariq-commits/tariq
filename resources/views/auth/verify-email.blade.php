<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Verify Your Email - TARIQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body{font-family:Inter,ui-sans-serif,system-ui,sans-serif}</style>
</head>
<body class="bg-slate-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-md text-center">
            <h1 class="text-xl font-bold mb-4">Verify your email address</h1>
            <p class="mb-4">A verification link was sent to your email address. Please check your inbox and click the link to verify.</p>
            <form method="POST" action="{{ route('verification.send') }}">@csrf
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded">Resend verification email</button>
            </form>
        </div>
    </div>
</body>
</html>
