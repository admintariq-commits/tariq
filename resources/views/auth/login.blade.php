@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
<h2 class="text-xl font-bold mb-4">Login</h2>
<form method="POST" action="{{ route('login.post') }}">
@csrf
<input type="email" name="email" placeholder="Email" class="w-full border p-2 mb-2 rounded">
<input type="password" name="password" placeholder="Password" class="w-full border p-2 mb-2 rounded">
<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Login</button>
</form>
</div>
@endsection
