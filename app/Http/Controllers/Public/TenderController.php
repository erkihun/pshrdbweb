<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Tender;
use Illuminate\Http\Request;

class TenderController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'q' => $request->input('q'),
            'status' => $request->input('status'),
        ];

        $query = Tender::published();

        if (!empty($filters['q'])) {
            $query->where(function ($builder) use ($filters) {
                $builder->where('title', 'like', '%' . $filters['q'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['q'] . '%');
            });
        }

        if ($filters['status'] === 'open') {
            $query->where(function ($builder) {
                $builder->whereNull('closing_date')
                    ->orWhere('closing_date', '>=', now());
            });
        } elseif ($filters['status'] === 'closed') {
            $query->whereNotNull('closing_date')
                ->whereDate('closing_date', '<', now());
        }

        $tenders = $query->orderByDesc('published_at')->paginate(10)->withQueryString();

        return view('tenders.index', [
            'tenders' => $tenders,
            'filters' => $filters,
        ]);
    }

    public function show(Tender $tender)
    {
        if (!$tender->isPublishedForPublic()) {
            abort(404);
        }

        $tender->increment('view_count');

        return view('tenders.show', [
            'tender' => $tender,
        ]);
    }
}
