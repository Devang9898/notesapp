<?php

use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\NoteController;

Route::get('/', function () {
    return view('welcome');
});

// Web Register/Login
Route::get('/register', [WebAuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [WebAuthController::class, 'register'])->name('register');

Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [WebAuthController::class, 'login'])->name('login');

// Protected Routes with PreventBackHistory middleware
Route::middleware(['auth', PreventBackHistory::class])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return view('dashboard', compact('user'));
    })->name('dashboard');
    Route::get('/notes/search', [NoteController::class, 'search'])->name('notes.search');
    Route::get('/notes/bookmarked', [NoteController::class, 'bookmarked'])->name('notes.bookmarked');
    // Note CRUD Routes
    Route::resource('notes', NoteController::class);
    
    // Route to toggle the favorite status of a note
    Route::put('/notes/{note}/toggle-favorite', [NoteController::class, 'toggleFavorite'])->name('notes.toggleFavorite');

    // ✅ PUT IT BACK HERE
    Route::post('notes/upload_attachment', [NoteController::class, 'uploadAttachment'])->name('notes.upload_attachment');

    
    // Logout Route
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
});

// Route::middleware(['auth', PreventBackHistory::class])->group(function () {
//     Route::get('/dashboard', function () {
//         $user = Auth::user();
//         return view('dashboard', compact('user'));
//     })->name('dashboard');

//     // Note CRUD Routes
//     Route::resource('notes', NoteController::class);

//     // Route to toggle the favorite status of a note
//     Route::put('/notes/{note}/toggle-favorite', [NoteController::class, 'toggleFavorite'])->name('notes.toggleFavorite');

//     // Route for bookmarked notes
//     Route::get('/notes/bookmarked', [NoteController::class, 'bookmarked'])->name('notes.bookmarked');

//     // Logout Route
//     Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
// });
Route::get('/verify-otp', [WebAuthController::class, 'showOtpForm'])->name('verify-otp.form');
Route::post('/verify-otp', [WebAuthController::class, 'verifyOtp'])->name('verify-otp');
Route::put('/notes/{note}/toggle-pin', [NoteController::class, 'togglePin'])->name('notes.togglePin');
Route::get('/my-bookmarks', [NoteController::class, 'userBookmarkedNotes'])->name('notes.user-bookmarked');
//Route::get('/notes/bookmarked', [NoteController::class, 'bookmarked'])->name('notes.bookmarked');