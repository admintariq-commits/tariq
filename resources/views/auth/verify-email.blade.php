@extends('layouts.guest')
@section('title', 'Verify Your Email - TARIQ')

@section('content')
    <div class="text-center">
        <h1 class="text-2xl font-bold mb-4">Verify your email address</h1>
        <p class="mb-4 text-slate-600">A verification link was sent to your email address. Please check your inbox and click the link to verify.</p>
        <form method="POST" action="{{ route('verification.send', [], false) }}">@csrf
            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg">Resend verification email</button>
        </form>
    </div>
@endsection
