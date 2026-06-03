@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Login</h2>
    <form method="POST" action="{{ route('login.post', [], false) }}">
        @csrf
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="w-full border p-2 mb-2 rounded">
        <input type="password" name="password" placeholder="Password" class="w-full border p-2 mb-2 rounded">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Login</button>
    </form>
    <div class="mt-4 text-sm text-center">
        <a href="{{ url('/forgot-password') }}" class="text-blue-600 hover:underline">Forgot your password?</a>
    </div>
</div>
@endsection
