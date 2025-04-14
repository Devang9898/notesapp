<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Token;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    /**
     * Web: Show registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Web: Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Web: Logout
     */
    public function webLogout(): RedirectResponse
    {
        Auth::logout();
        return redirect('/login')->with('status', 'You have been logged out.');
    }

    /**
     * API: Register
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('API Token')->accessToken;

        return response()->json([
            'message' => 'User registered successfully',
            'token'   => $token,
            'user'    => $user
        ], 201);
    }

    /**
     * API: Login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user  = Auth::user();
            $token = $user->createToken('API Token')->accessToken;

            return response()->json([
                'message' => 'Login successful',
                'token'   => $token,
                'user'    => $user
            ]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    /**
     * API: Logout
     */
    public function apiLogout(Request $request)
    {
        $user = $request->user();

        if ($user && $user->token()) {
            $user->token()->revoke();

            return response()->json([
                'message' => 'API user logged out successfully'
            ]);
        }

        return response()->json([
            'error' => 'Unable to logout. User not authenticated.'
        ], 401);
    }
}




// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\User;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Validator;

// class AuthController extends Controller
// {
//     public function showRegisterForm()
//     {
//         return view('auth.register');
//     }

//     public function register(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'required|email|unique:users',
//             'password' => 'required|min:6|confirmed',
//         ]);

//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => bcrypt($request->password),
//         ]);

//         Auth::login($user);

//         return redirect()->route('dashboard');
//     }


//     public function showLoginForm()
//     {
//         return view('auth.login');
//     }

//     public function login(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'email'    => 'required|email',
//             'password' => 'required|string',
//         ]);

//         if ($validator->fails()) {
//             return redirect()->back()->withErrors($validator)->withInput();
//         }

//         if (Auth::attempt($request->only('email', 'password'))) {
//             $user = Auth::user();
//             $token = $user->createToken('Personal Access Token')->accessToken;

//             session(['access_token' => $token]);

//             return redirect()->route('dashboard');
//         }

//         return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
//     }
//     public function logout(Request $request)
//     {
//         $user = $request->user();

//         // Check if the user is authenticated and if a token exists
//         if ($user && method_exists($user, 'token') && $user->token()) {
//             $user->token()->revoke(); // For API token users
//         }

//         Auth::logout(); // For web users
//         $request->session()->invalidate();
//         $request->session()->regenerateToken();

//         return redirect()->route('login.form');
//     }

    
// }


// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// class AuthController extends Controller
// {
//     //
// }
