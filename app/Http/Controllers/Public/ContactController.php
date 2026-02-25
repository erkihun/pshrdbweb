<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;

class ContactController extends Controller
{
    public function index()
    {
        $contactInfo = ContactInfo::current();

        return view('contact.index', compact('contactInfo'));
    }
}
