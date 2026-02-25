<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMouRequest;
use App\Http\Requests\UpdateMouRequest;
use App\Models\Mou;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MouController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage mous');
    }

    public function index(Request $request)
    {
        $query = Mou::with('partner');

        if ($request->filled('partner')) {
            $query->where('partner_id', $request->partner);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('published')) {
            $query->where('is_published', $request->boolean('published'));
        }

        if ($request->filled('signed_from')) {
            $query->whereDate('signed_at', '>=', $request->signed_from);
        }

        if ($request->filled('signed_to')) {
            $query->whereDate('signed_at', '<=', $request->signed_to);
        }

        $mous = $query->orderByDesc('signed_at')->paginate(10)->withQueryString();
        $partners = Partner::where('is_active', true)->orderBy('name_am')->get();

        return view('admin.mous.index', compact('mous', 'partners'));
    }

    public function create()
    {
        $partners = Partner::where('is_active', true)->orderBy('name_am')->get();

        return view('admin.mous.create', compact('partners'));
    }

    public function store(StoreMouRequest $request)
    {
        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published');
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('mous/attachments', 'public');
        }

        Mou::create($data);

        return redirect()->route('admin.mous.index')->with('success', 'MoU created.');
    }

    public function show(Mou $mou)
    {
        $mou->load('partner');

        return view('admin.mous.show', compact('mou'));
    }

    public function edit(Mou $mou)
    {
        $partners = Partner::where('is_active', true)->orderBy('name_am')->get();

        return view('admin.mous.edit', compact('mou', 'partners'));
    }

    public function update(UpdateMouRequest $request, Mou $mou)
    {
        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published');
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            if ($mou->attachment_path) {
                Storage::disk('public')->delete($mou->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')->store('mous/attachments', 'public');
        }

        $mou->update($data);

        return redirect()->route('admin.mous.index')->with('success', 'MoU updated.');
    }

    public function destroy(Mou $mou)
    {
        if ($mou->attachment_path) {
            Storage::disk('public')->delete($mou->attachment_path);
        }

        $mou->delete();

        return redirect()->route('admin.mous.index')->with('success', 'MoU deleted.');
    }
}
