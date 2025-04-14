@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Side - Welcome Message -->
    <div class="w-1/2 bg-gradient-to-br from-purple-500 via-indigo-600 to-blue-500 text-white flex items-center justify-center px-10">
        <div class="text-center space-y-4">
            <h1 class="text-5xl font-extrabold mb-2 drop-shadow-lg">🚀 NoteKeeper</h1>
            <p class="text-lg opacity-90">Organize your thoughts. Access your notes anytime, anywhere.</p>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-1/2 flex items-center justify-center bg-gray-50">
        <div class="w-full max-w-md p-10 rounded-2xl shadow-xl bg-white border border-gray-200">
            <h2 class="text-3xl font-semibold mb-6 text-gray-800 text-center">Welcome Back 👋</h2>

            <form method="POST" action="{{ route('login') }}" onsubmit="return validateLoginForm()" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}" 
                            placeholder="you@example.com"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 transition @error('email') border-red-500 @enderror"
                            required
                        />
                        <span class="absolute left-3 top-3.5 text-gray-400 text-lg">📧</span>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="••••••••"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 transition @error('password') border-red-500 @enderror"
                            required
                        />
                        <span class="absolute left-3 top-3.5 text-gray-400 text-lg">🔒</span>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div>
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 shadow-md">
                        Login
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register.form') }}" class="text-indigo-600 font-medium hover:underline">Register here</a>
            </p>
        </div>
    </div>
</div>

{{-- Client-side JS Validation --}}
<script>
function validateLoginForm() {
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    let valid = true;

    document.querySelectorAll('.client-error').forEach(el => el.remove());

    function showError(input, message) {
        const error = document.createElement('p');
        error.className = 'text-red-500 text-sm mt-1 client-error';
        error.innerText = message;
        input.classList.add('border-red-500');
        input.parentNode.appendChild(error);
    }

    if (!email.value.trim()) {
        showError(email, "Email is required");
        valid = false;
    } else {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            showError(email, "Enter a valid email address.");
            valid = false;
        }
    }

    if (!password.value.trim()) {
        showError(password, "Password is required");
        valid = false;
    } else if (password.value.trim().length < 8) {
        showError(password, "Password must be at least 8 characters.");
        valid = false;
    }

    return valid;
}
</script>
@endsection
