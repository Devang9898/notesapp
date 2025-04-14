<!DOCTYPE html>
<html lang="en">


<head>
    <!-- Trix Editor CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.4/trix.min.css">

    <!-- Trix Editor JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.4/trix.min.js"></script>
    <meta charset="UTF-8">
    <title>NoteKeeper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-white shadow-md py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-xl font-bold text-green-600">NoteKeeper</a>

            <div class="space-x-4">
                @auth
                <div class="flex items-center space-x-3">
        {{-- User Initial Badge --}}
        {{-- User Initial Badge as Button --}}
{{--<a href="{{ route('register.form') }}" title="Go to Register Page">--}}
    <div class="w-9 h-9 flex items-center justify-center bg-green-500 text-white font-bold rounded-full uppercase cursor-pointer hover:bg-green-600 transition">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>
{{--</a>--}

        
        {{-- User Name and Logout --}}
        <span class="text-gray-700">Hi, {{ auth()->user()->name }}</span>
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">Logout</button>
        </form>
    </div>
                @else
                    <a href="{{ url('/') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Login</a>
                    <a href="{{ route('register.form') }}" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">Register</a>

                @endauth
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main class="flex-grow container mx-auto px-4 py-8">
        @yield('content')
    </main>

    {{-- Footer (optional) --}}
    <footer class="bg-white shadow-inner py-4 text-center text-sm text-gray-500">
        © {{ date('Y') }} NoteKeeper. All rights reserved.
    </footer>

</body>
</html>
