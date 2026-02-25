<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\SignageDisplays\CreateSignageDisplayAction;
use App\Actions\SignageDisplays\DeleteSignageDisplayAction;
use App\Actions\SignageDisplays\UpdateSignageDisplayAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SignageDisplayStoreRequest;
use App\Http\Requests\Admin\SignageDisplayUpdateRequest;
use App\Models\SignageDisplay;
use App\Models\SignageTemplate;

final class SignageDisplayController extends Controller
{
    public function index()
    {
        $displays = SignageDisplay::with('template')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.signage.displays.index', compact('displays'));
    }

    public function create()
    {
        $templates = SignageTemplate::orderBy('name_en')->get();

        return view('admin.signage.displays.create', compact('templates'));
    }

    public function store(SignageDisplayStoreRequest $request, CreateSignageDisplayAction $action)
    {
        $display = $action->execute($request->validated());

        return redirect()
            ->route('admin.signage.displays.show', $display)
            ->with('success', 'Signage display created successfully.');
    }

    public function show(SignageDisplay $display)
    {
        $display->load('template');

        return view('admin.signage.displays.show', ['display' => $display]);
    }

    public function edit(SignageDisplay $display)
    {
        $templates = SignageTemplate::orderBy('name_en')->get();

        return view('admin.signage.displays.edit', [
            'display' => $display,
            'templates' => $templates,
        ]);
    }

    public function update(
        SignageDisplayUpdateRequest $request,
        SignageDisplay $display,
        UpdateSignageDisplayAction $action
    ) {
        $action->execute($display, $request->validated());

        return redirect()
            ->route('admin.signage.displays.show', $display)
            ->with('success', 'Signage display updated successfully.');
    }

    public function destroy(SignageDisplay $display, DeleteSignageDisplayAction $action)
    {
        $action->execute($display);

        return redirect()
            ->route('admin.signage.displays.index')
            ->with('success', 'Signage display deleted.');
    }
}
