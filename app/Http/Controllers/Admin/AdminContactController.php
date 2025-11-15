<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class AdminContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(20);

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy($id)
    {
        Contact::destroy($id);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Đã xoá liên hệ.');
    }
}
