@extends('layouts.guest')
@section('title', 'Reset Password - TARIQ')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-xl ring-1 ring-slate-200">
    <h2 class="text-2xl font-bold mb-4">Reset your password</h2>
    <p class="mb-4 text-sm text-gray-600">Set a new password for your account.</p>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="email" name="email" value="{{ old('email', $email) }}" placeholder="Email" class="w-full border p-2 mb-2 rounded">
        <input type="password" name="password" placeholder="New password" class="w-full border p-2 mb-2 rounded">
        <input type="password" name="password_confirmation" placeholder="Confirm password" class="w-full border p-2 mb-2 rounded">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Reset password</button>
    </form>

    <div class="mt-4 text-sm text-center">
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Back to login</a>
    </div>
</div>
@endsection
