<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        // ここは“最小”：後で検索・日付・CSVを足す
        $contacts = Contact::with('category')->latest()->paginate(7);
        return view('admin.contacts.index', compact('contacts'));
    } //
}
