<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ======================= LOGIN =======================
    public function showLoginForm()
    {
        return view('index.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Tìm user theo email
        $user = User::where('Email', $request->email)->first();

        // ✅ Kiểm tra mật khẩu (đã mã hóa)
        if (Hash::check($request->password, $user->Password)) {
            Auth::login($user);
            if($user->Role==='admin'){
                return redirect()->route('admin.dashboard')->with('success', 'Admin đăng nhập thành công!');
            }
            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        }

        // đạt cập nhật 5/11/2025: thêm thông báo email không tồn tại
        else if(!$user){
            return back()->with('error', 'Email không tồn tại');
        }
        
        // ❌ Nếu sai thông tin
        return back()->with('error', 'Email hoặc mật khẩu không đúng');
    }

    // ======================= SIGNUP =======================
    public function showSignUpForm()
    {
        return view('index.signup');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|max:50',
            'last_name'    => 'required|string|max:50',
            'phone_number' => 'required|string|max:15',
            'address'      => 'required|string|max:255',
            'email'        => 'required|email|unique:users,Email',
            'password'     => 'required|min:6',
        ]);

        // ✅ Lưu tài khoản mới vào DB (mã hóa mật khẩu)
        $user = User::create([
            'FirstName'   => $request->first_name,
            'LastName'    => $request->last_name,
            'PhoneNumber' => $request->phone_number,
            'Address'     => $request->address,
            'Email'       => $request->email,
            'Password'    => Hash::make($request->password), 
            'Role'        => 'customer',
        ]);

        // Đăng nhập ngay sau khi đăng ký
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký thành công!');
    }

    // ======================= LOGOUT =======================
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }
}
