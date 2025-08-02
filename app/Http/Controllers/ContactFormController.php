<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Category;
use App\Models\Contact;

class ContactFormController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id')->get();
        return view('contacts.index', compact('categories'));
    }

    public function confirm(ContactRequest $request)
    {
        $data = $request->validated();
        $category = Category::find($data['category_id']);
        return view('contacts.confirm', [
            'data' => $data,
            'category' => $category?->content,
        ]);
    }

    public function store(ContactRequest $request)
    {
        $data = $request->validated();

        Contact::create([
            'last_name'   => $data['last_name'],
            'first_name'  => $data['first_name'],
            'gender'      => $data['gender'],
            'email'       => $data['email'],
            'tel'         => $data['tel'],
            'address'     => $data['address'],
            'building'    => $data['building'] ?? null,
            'category_id' => $data['category_id'],
            'detail'      => $data['detail'],
        ]);

        return view('contacts.thanks');
    }
}
