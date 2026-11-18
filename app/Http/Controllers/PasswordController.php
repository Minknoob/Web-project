<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;

class PasswordController extends Controller
{
    // Hiển thị form nhập email
    public function showForgotForm()
    {
        return view('reset-password.forgot');
    }

    // Gửi email reset password
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors([
            'email' => __($status)
        ]);
    }

    // Hiển thị form nhập mật khẩu mới
    public function showResetForm(Request $request, string $token)
    {
        return view('reset-password.reset', [
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }

    // Xử lý cập nhật mật khẩu
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()]
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function (User $user, string $password) {
                $user->password = Hash::make($password);

                // Tạo remember token mới
                $user->setRememberToken(Str::random(60));

                $user->save();

                // Phát sự kiện PasswordReset
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('status', __($status));
        }

        return back()
            ->withErrors(['email' => [__($status)]]);
    }
}
