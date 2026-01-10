<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VacancyController extends Controller
{
    public function index(Request $request): View
    {
        $vacancyQuery = Vacancy::visibleForPublic();
        $totalSlots = (int) $vacancyQuery->sum('slots');
        $vacancies = $vacancyQuery
            ->orderBy('deadline')
            ->paginate(9)
            ->withQueryString();

        $seoMeta = [
            'title' => __('vacancies.public.title'),
            'description' => __('vacancies.public.seo_list_description'),
            'canonical' => route('vacancies.index'),
            'type' => 'website',
        ];

        return view('public.vacancies.index', [
            'vacancies' => $vacancies,
            'totalSlots' => $totalSlots,
            'seoMeta' => $seoMeta,
        ]);
    }

    public function show(string $slug): View
    {
        $vacancy = Vacancy::publishedForPublic()
            ->where(function ($query) use ($slug) {
                $query->where('id', $slug)
                    ->orWhere('code', $slug);
            })
            ->firstOrFail();

        $seoMeta = [
            'title' => $vacancy->title,
            'description' => str($vacancy->description)->limit(160)->__toString(),
            'canonical' => route('vacancies.show', ['slug' => $vacancy->public_slug]),
            'type' => 'article',
        ];

        return view('public.vacancies.show', [
            'vacancy' => $vacancy,
            'seoMeta' => $seoMeta,
        ]);
    }
}
