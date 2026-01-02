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

    public function show(SignageDisplay $signage_display)
    {
        $signage_display->load('template');

        return view('admin.signage.displays.show', ['display' => $signage_display]);
    }

    public function edit(SignageDisplay $signage_display)
    {
        $templates = SignageTemplate::orderBy('name_en')->get();

        return view('admin.signage.displays.edit', [
            'display' => $signage_display,
            'templates' => $templates,
        ]);
    }

    public function update(
        SignageDisplayUpdateRequest $request,
        SignageDisplay $signage_display,
        UpdateSignageDisplayAction $action
    ) {
        $action->execute($signage_display, $request->validated());

        return redirect()
            ->route('admin.signage.displays.show', $signage_display)
            ->with('success', 'Signage display updated successfully.');
    }

    public function destroy(SignageDisplay $signage_display, DeleteSignageDisplayAction $action)
    {
        $action->execute($signage_display);

        return redirect()
            ->route('admin.signage.displays.index')
            ->with('success', 'Signage display deleted.');
    }
}
