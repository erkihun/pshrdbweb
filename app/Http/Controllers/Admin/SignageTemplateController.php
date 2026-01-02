<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\SignageTemplates\CreateSignageTemplateAction;
use App\Actions\SignageTemplates\DeleteSignageTemplateAction;
use App\Actions\SignageTemplates\UpdateSignageTemplateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SignageTemplateStoreRequest;
use App\Http\Requests\Admin\SignageTemplateUpdateRequest;
use App\Models\SignageTemplate;

final class SignageTemplateController extends Controller
{
    public function index()
    {
        $templates = SignageTemplate::query()
            ->withCount('displays')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.signage.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.signage.templates.create');
    }

    public function store(SignageTemplateStoreRequest $request, CreateSignageTemplateAction $action)
    {
        $template = $action->execute($request->validated());

        return redirect()
            ->route('admin.signage.templates.show', $template)
            ->with('success', 'Signage template created successfully.');
    }

    public function show(SignageTemplate $template)
    {
        $template->load('displays');

        return view('admin.signage.templates.show', ['template' => $template]);
    }

    public function edit(SignageTemplate $template)
    {
        return view('admin.signage.templates.edit', ['template' => $template]);
    }

    public function update(
        SignageTemplateUpdateRequest $request,
        SignageTemplate $template,
        UpdateSignageTemplateAction $action
    ) {
        $action->execute($template, $request->validated());

        return redirect()
            ->route('admin.signage.templates.show', $template)
            ->with('success', 'Signage template updated successfully.');
    }

    public function destroy(SignageTemplate $template, DeleteSignageTemplateAction $action)
    {
        $action->execute($template);

        return redirect()
            ->route('admin.signage.templates.index')
            ->with('success', 'Signage template deleted.');
    }
}
