<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginGoogleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function redirectToGoogle()
    {
        $google = Socialite::driver('google')->redirect();

        // Lấy URL redirect mặc định
        $url = $google->getTargetUrl();

        // Thêm prompt=select_account
        $url .= '&prompt=select_account';

        return redirect($url);
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 1️⃣ Nếu tài khoản đã có google_id → login không đổi dữ liệu
            $user = User::where('google_id', $googleUser->id)->first();
            if ($user) {
                Auth::login($user);
                return redirect()->intended('/');
            }

            // 2️⃣ Nếu email đã tồn tại → chỉ liên kết google_id (KHÔNG ghi đè dữ liệu)
            $existingUser = User::where('Email', $googleUser->email)->first();
            if ($existingUser) {

                // chỉ cập nhật google_id, không sửa firstname, lastname, phone, address,...
                $existingUser->update([
                    'google_id' => $googleUser->id
                ]);

                Auth::login($existingUser);
                return redirect()->intended('/');
            }

            // 3️⃣ Nếu user hoàn toàn mới → tạo mới
            $newUser = User::create([
                'FirstName' => '',
                'LastName' => $googleUser->name,
                'Email' => $googleUser->email,
                'PhoneNumber' => "",
                'Address' => "",
                'Role' => "customer",
                'google_id' => $googleUser->id,
                'Avatar' => $googleUser->avatar, 
                'Password' => bcrypt('123456789')
            ]);

            Auth::login($newUser);
            return redirect()->intended('/');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
