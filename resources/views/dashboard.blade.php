
@extends('layouts.app')

@section('content')
<div class="container mt-10">
    <h2 class="text-2xl font-semibold">Welcome, {{ Auth::user()->name }}!</h2>
    <p class="mt-2 text-gray-600">
        You are logged in with email: <strong>{{ Auth::user()->email }}</strong>.
    </p>

    {{-- Logout Form --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>
@endsection
