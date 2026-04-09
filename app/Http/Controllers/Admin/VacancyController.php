<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVacancyRequest;
use App\Http\Requests\Admin\UpdateVacancyRequest;
use App\Models\Vacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VacancyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Vacancy::query();

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $vacancies = $query->orderByDesc('created_at')->paginate(12)->withQueryString();

        return view('admin.vacancies.index', [
            'vacancies' => $vacancies,
        ]);
    }

    public function create(): View
    {
        return view('admin.vacancies.create');
    }

    public function store(StoreVacancyRequest $request): RedirectResponse
    {
        Vacancy::create(array_merge($request->validated(), [
            'location' => null,
            'status' => Vacancy::STATUS_OPEN,
            'published_at' => null,
            'is_published' => false,
            'code' => null,
            'notes' => null,
            'slots' => 1,
        ]));

        return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy announcement created.');
    }

    public function show(Vacancy $vacancy): View
    {
        return view('admin.vacancies.show', [
            'vacancy' => $vacancy,
        ]);
    }

    public function edit(Vacancy $vacancy): View
    {
        return view('admin.vacancies.edit', [
            'vacancy' => $vacancy,
        ]);
    }

    public function update(UpdateVacancyRequest $request, Vacancy $vacancy): RedirectResponse
    {
        $vacancy->update(array_merge($request->validated(), [
            'location' => null,
            'status' => Vacancy::STATUS_OPEN,
            'code' => null,
            'notes' => null,
            'slots' => 1,
        ]));

        return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy announcement updated.');
    }

    public function destroy(Vacancy $vacancy): RedirectResponse
    {
        $vacancy->delete();

        return back()->with('success', 'Vacancy announcement deleted.');
    }

    public function publish(Vacancy $vacancy): RedirectResponse
    {
        $vacancy->update([
            'is_published' => true,
            'published_at' => $vacancy->published_at ?: now(),
        ]);

        return redirect()
            ->route('admin.vacancies.index')
            ->with('success', 'Vacancy announcement published.');
    }

    public function unpublish(Vacancy $vacancy): RedirectResponse
    {
        $vacancy->update([
            'is_published' => false,
        ]);

        return redirect()
            ->route('admin.vacancies.index')
            ->with('success', 'Vacancy announcement unpublished.');
    }
}
