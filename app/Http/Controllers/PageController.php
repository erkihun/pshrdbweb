<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function show(string $key)
    {
        $page = Page::where('key', $key)
            ->where('is_published', true)
            ->firstOrFail();

        $localeTitle = app()->getLocale() === 'am' ? 'title_am' : 'title_en';
        $pages = Page::where('is_published', true)
            ->orderBy($localeTitle)
            ->get();

        return view('pages.show', compact('page', 'pages'));
    }
}
