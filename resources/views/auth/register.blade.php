@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-green-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
        
        {{-- Left Side – Welcome Section --}}
        <div class="bg-gradient-to-tr from-green-400 via-blue-400 to-indigo-500 text-white p-10 flex flex-col justify-center items-center">
            <h2 class="text-4xl font-extrabold mb-4 tracking-wide drop-shadow">Welcome to NoteX</h2>
            <p class="text-lg text-center font-medium opacity-90">Your smart space to note, organize, and never lose your thoughts again.</p>
            <img src="https://img.icons8.com/ios-filled/100/note.png" class="mt-8 animate-bounce" alt="Note Icon"/>
        </div>

        {{-- Right Side – Registration Form --}}
        <div class="p-10 bg-white">
            <h3 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Create Your Account</h3>

            <form method="POST" action="{{ route('register') }}" onsubmit="return validateRegisterForm()" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <input type="text" name="name" placeholder="Full Name"
                        value="{{ old('name') }}"
                        onkeypress="return isAlphabetKey(event)"
                        onpaste="return false"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <input type="email" name="email" placeholder="Email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 @error('email') border-red-500 @enderror"
                        required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <input type="password" name="password" placeholder="Password (8 digits only)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('password') border-red-500 @enderror"
                        required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        required>
                </div>

                <div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-green-400 to-blue-500 text-white py-3 rounded-lg font-bold shadow-md hover:shadow-xl transform hover:scale-105 transition duration-300">
                        Register
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-gray-600 font-medium">
                Already have an account?
                <a href="{{ url('/') }}" class="text-blue-600 hover:underline font-semibold">Login here</a>
            </p>
        </div>
    </div>
</div>

{{-- Client-side validation script --}}
<script>
    function validateRegisterForm() {
        const form = document.forms[0];
        const name = form["name"];
        const email = form["email"];
        const password = form["password"];
        const confirmPassword = form["password_confirmation"];

        // Clear previous client errors
        document.querySelectorAll('.client-error').forEach(e => e.remove());
        [name, email, password, confirmPassword].forEach(field => field.classList.remove('border-red-500'));

        let valid = true;

        function showError(input, message) {
            const error = document.createElement('p');
            error.className = 'text-red-500 text-sm mt-1 client-error';
            error.innerText = message;
            input.classList.add('border-red-500');
            input.parentNode.appendChild(error);
        }

        // Name validation
        if (!name.value.trim()) {
            showError(name, "Full Name is required.");
            valid = false;
        } else if (!/^[a-zA-Z\s]+$/.test(name.value.trim())) {
            showError(name, "Full Name must contain only letters and spaces.");
            valid = false;
        }

        // Email validation
        if (!email.value.trim()) {
            showError(email, "Email is required.");
            valid = false;
        } else {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value.trim())) {
                showError(email, "Enter a valid email address.");
                valid = false;
            }
        }

        // Password validation
        if (!password.value.trim()) {
            showError(password, "Password is required.");
            valid = false;
        } else if (!/^\d{8}$/.test(password.value)) {
            showError(password, "Password must be exactly 8 digits.");
            valid = false;
        }

        // Confirm Password
        if (!confirmPassword.value.trim()) {
            showError(confirmPassword, "Confirm Password is required.");
            valid = false;
        } else if (password.value !== confirmPassword.value) {
            showError(confirmPassword, "Passwords do not match.");
            valid = false;
        }

        return valid;
    }

    function isAlphabetKey(evt) {
        const charCode = evt.which || evt.keyCode;
        const charStr = String.fromCharCode(charCode);
        if (!/^[a-zA-Z\s]$/.test(charStr)) {
            evt.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endsection
