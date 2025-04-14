@extends('layouts.app')

@section('content')
<div class="min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md p-8 shadow-lg rounded-lg border">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Enter OTP</h2>

        <form method="POST" action="{{ route('verify-otp') }}">
            @csrf

            <div class="mb-6">
                <label for="otp" class="block mb-1 text-gray-700">OTP</label>
                <input type="text" name="otp" id="otp" class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter OTP" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700 transition duration-300">
                Verify OTP
            </button>
        </form>
    </div>
</div>
@endsection
