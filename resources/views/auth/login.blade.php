@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <h2 class="text-2xl mb-4">Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" class="w-full p-2 mb-3 border" placeholder="Email" required>
        <input type="password" name="password" class="w-full p-2 mb-3 border" placeholder="Password" required>
        <button class="bg-blue-500 text-white px-4 py-2 rounded">Login</button>
    </form>
    <p class="mt-4">Don't have an account? <a href="{{ route('register.form') }}" class="text-blue-600">Register here</a></p>
</div>
@endsection
