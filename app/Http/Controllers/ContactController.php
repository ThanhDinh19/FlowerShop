<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // Hiển thị form liên hệ
    public function showForm()
    {
        return view('index.contact');
    }

    // Xử lý khi người dùng gửi form
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:1000',
        ]);

        // Lưu thông tin liên hệ vào database
        Contact::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Cảm ơn bạn! Flower Shop đã nhận được liên hệ của bạn 💌');
    }
}
