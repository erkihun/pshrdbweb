<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStayConnectedRequest;
use App\Http\Requests\UpdateStayConnectedRequest;
use App\Models\StayConnected;

class StayConnectedController extends Controller
{
    public function index()
    {
        $items = StayConnected::query()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.stay-connected.index', compact('items'));
    }

    public function create()
    {
        return view('admin.stay-connected.create');
    }

    public function store(StoreStayConnectedRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        StayConnected::create($data);

        return to_route('admin.stay-connected.index')->with('success', 'Stay connected item created.');
    }

    public function edit(StayConnected $stayConnected)
    {
        return view('admin.stay-connected.edit', compact('stayConnected'));
    }

    public function update(UpdateStayConnectedRequest $request, StayConnected $stayConnected)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $stayConnected->update($data);

        return to_route('admin.stay-connected.index')->with('success', 'Stay connected item updated.');
    }

    public function destroy(StayConnected $stayConnected)
    {
        $stayConnected->delete();

        return to_route('admin.stay-connected.index')->with('success', 'Stay connected item deleted.');
    }
}
