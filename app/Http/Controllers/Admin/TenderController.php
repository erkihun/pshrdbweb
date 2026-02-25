<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TenderController extends Controller
{
    public function index(Request $request)
    {
        $query = Tender::query();
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tenders = $query->orderByDesc('published_at')->paginate(10)->withQueryString();

        return view('admin.tenders.index', [
            'tenders' => $tenders,
            'statuses' => Tender::statuses(),
        ]);
    }

    public function create()
    {
        return view('admin.tenders.create', [
            'statuses' => Tender::statuses(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'budget' => ['nullable', 'numeric'],
            'status' => ['required', 'in:' . implode(',', Tender::statuses())],
            'published_at' => ['nullable', 'date'],
        ]);

        Tender::create($validated);

        return redirect()->route('admin.tenders.index')->with('success', 'Tender created.');
    }

    public function edit(Tender $tender)
    {
        return view('admin.tenders.edit', [
            'tender' => $tender,
            'statuses' => Tender::statuses(),
        ]);
    }

    public function update(Request $request, Tender $tender): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'budget' => ['nullable', 'numeric'],
            'status' => ['required', 'in:' . implode(',', Tender::statuses())],
            'published_at' => ['nullable', 'date'],
        ]);

        $tender->update($validated);

        return redirect()->route('admin.tenders.index')->with('success', 'Tender updated.');
    }

    public function destroy(Tender $tender): RedirectResponse
    {
        $tender->delete();

        return back()->with('success', 'Tender removed.');
    }
}
