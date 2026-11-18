<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // Hiển thị trang Profile
    public function edit()
    {
        return view('profile.edit');
    }

    // Cập nhật username hoặc email
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') &&
            $request->email !== $user->email) {

            $user->email = $request->email;

            // Reset trạng thái xác thực email
            $user->email_verified_at = null;

            $user->save();

            // Gửi email xác thực mới
            $user->sendEmailVerificationNotification();

            return back()->with(
                'success',
                'Email updated. Please check your inbox to verify it.'
            );
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    // Đổi mật khẩu
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user = Auth::user();

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    // Upload avatar
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $user = Auth::user();

        // Xóa avatar cũ nếu có
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Lưu ảnh mới
        $path = $request->file('avatar')
            ->store('profile-avatars', 'public');

        $user->avatar = $path;
        $user->save();

        return back()->with('success', 'Avatar updated successfully.');
    }

    // Xóa avatar
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);

            $user->avatar = null;
            $user->save();
        }

        return back()->with('success', 'Avatar removed successfully.');
    }
}
