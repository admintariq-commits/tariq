@extends('layouts.guest')
@section('title', 'Forgot Password - TARIQ')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-xl ring-1 ring-slate-200">
    <h2 class="text-2xl font-bold mb-4">Forgot your password?</h2>
    <p class="mb-4 text-sm text-gray-600">Enter your email address and we will send a link to reset your password.</p>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="w-full border p-2 mb-2 rounded">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Send reset link</button>
    </form>

    <div class="mt-4 text-sm text-center">
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Back to login</a>
    </div>
</div>
@endsection
