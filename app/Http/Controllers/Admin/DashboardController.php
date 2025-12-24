<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Post;
use App\Models\Service;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $serviceCount = Service::where('is_active', true)->count();
        $postCount = Post::where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->count();
        $documentCount = Document::where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->count();
        $openTickets = Ticket::where('status', 'open')->count();
        $recentTickets = Ticket::latest()->limit(5)->get();

        return view('admin.dashboard', compact('serviceCount', 'postCount', 'documentCount', 'openTickets', 'recentTickets'));
    }
}
