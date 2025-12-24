<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private SearchService $searchService)
    {
    }

    public function index(Request $request)
    {
        $results = $this->searchService->search($request->get('q', ''), app()->getLocale(), [
            'posts' => $request->get('posts_page'),
            'services' => $request->get('services_page'),
            'documents' => $request->get('documents_page'),
            'pages' => $request->get('pages_page'),
        ]);

        return view('search.index', $results);
    }
}
