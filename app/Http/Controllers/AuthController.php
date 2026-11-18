<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{

    // -------------Default-------------
    public function defaultRoute() {
        if (Auth::check()) {
            return view('home');
        } else {
            return view('index');
        }
    }

    // --------------Login--------------

    public function showLogin() {
        return view('authentication.login');
    }

    public function login(Request $request) {
        // Validate dữ liệu
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ], [
            'email.required' => 'Please enter your email.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ]);

        // Kiểm tra tài khoản
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect('/home');
        }

        // Đăng nhập thất bại
        return back()->withErrors([
            'password' => 'Email or password is incorrect!'
        ])->onlyInput('email');
    }



    // --------------Register--------------

    public function showRegister() {
        return view('authentication.register');
    }

    public function register(Request $request) {
        // Validate dữ liệu
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'name' => 'required|max:255',
            'password' => ['required', 'confirmed', Password::defaults()]
        ], [
            'email.required' => 'Please enter your email.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'Email already existed.',
            'name.required' => 'Please enter your name.',
            'password.required' => 'Please enter your password.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must have at least 4 symbols.',
            'password.mixed' => 'Password must contain both lower case and upper case.',
            'password.numbers' => 'Password must contain at least 1 number.',
            'password.symbols' => 'Password must contain at least 1 special symbol.'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home');
    }

    // --------------Logout--------------
    public function logout(Request $request) {
        Auth::logout();

        // Hủy session hiện tại
        $request->session()->invalidate();

        // Tạo lại CSRF token
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
