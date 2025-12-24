<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        return view('admin.search', [
            'query' => $query,
            'hasQuery' => $query !== '',
        ]);
    }
}
