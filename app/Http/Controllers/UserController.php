<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //// hiển thị thông tin cá nhân người dùng
    public function showProfile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }


    // Cập nhật thông tin hồ sơ
    public function updateProfile(Request $request)
    {
        $request->validate([
            'FirstName' => 'required|string|max:50',
            'LastName' => 'required|string|max:50',
            'PhoneNumber' => 'nullable|string|max:15',
            'Address' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update($request->only(['FirstName', 'LastName', 'PhoneNumber', 'Address']));

        return redirect()->route('profile.show')->with('success', 'Cập nhật thông tin thành công!');
    }
}
