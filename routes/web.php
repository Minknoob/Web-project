<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/homecopy', function () {
    return view('homecopy');
});

// Index 
Route::get('/', [AuthController::class, 'defaultRoute'])->name('default');

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');

Route::post('/login', [AuthController::class, 'login']);


// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::post('/register', [AuthController::class, 'register']);


// Home
Route::get('/home', [NoteController::class, 'index'])
    ->middleware('auth')
    ->name('home');

require __DIR__.'/notes.php';


// Email Verification
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {

    $request->fulfill();

    return redirect()->route('home')
        ->with('success', 'Email verified successfully!');

})->middleware(['auth', 'signed'])
  ->name('verification.verify');


// Resend Email Verification
Route::post('/email/verification-notification', function (Request $request) {

    // Gửi lại email xác thực
    $request->user()->sendEmailVerificationNotification();

    return back()->with('success', 'Verification link sent!');

})->middleware(['auth', 'throttle:6,1'])
  ->name('verification.send');


// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');


// Reset Password
// Hiển thị form nhập email
Route::get('/forgot-password', [PasswordController::class, 'showForgotForm'])
    ->name('password.request');

// Gửi email reset password
Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])
    ->name('password.email');

// Hiển thị form nhập mật khẩu mới
Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])
    ->name('password.reset');

// Xử lý cập nhật mật khẩu
Route::post('/reset-password', [PasswordController::class, 'resetPassword'])
    ->name('password.update');


// Edit Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('auth')
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->middleware('auth')
        ->name('profile.update');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->middleware('auth')
        ->name('profile.password.update');

    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
        ->middleware('auth')
        ->name('profile.avatar.update');

    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])
        ->middleware('auth')
        ->name('profile.avatar.destroy');
});
