<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Mou;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MouController extends Controller
{
    public function index(Request $request)
    {
        $query = Mou::with('partner')
            ->where('is_published', true);

        $query->when($request->filled('partner'), function ($builder) use ($request) {
            $builder->where('partner_id', $request->partner);
        });

        $query->when(in_array($request->status, ['active', 'expired'], true), function ($builder) use ($request) {
            $builder->where('status', $request->status);
        });

        $query->when($request->filled('year'), function ($builder) use ($request) {
            $builder->whereYear('signed_at', $request->year);
        });

        $mous = $query->orderByDesc('signed_at')->paginate(10)->withQueryString();

        $partners = Partner::where('is_active', true)->orderBy('name_am')->get();
        $years = Mou::query()
            ->selectRaw('YEAR(signed_at) as year')
            ->whereNotNull('signed_at')
            ->where('is_published', true)
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->filter()
            ->values();

        $breadcrumbItems = [
            ['label' => __('public.navigation.partnerships'), 'url' => route('public.mous.index')],
            ['label' => __('mous.public.title'), 'url' => route('public.mous.index')],
        ];

        return view('mous.index', compact('mous', 'partners', 'years', 'breadcrumbItems'));
    }

    public function show(string $identifier)
    {
        $query = Mou::with('partner')->where('is_published', true);

        if (Str::isUuid($identifier)) {
            $mou = $query->where('id', $identifier)->first();
        } else {
            $mou = $query->where('reference_no', $identifier)->first();
            if (! $mou) {
                $slug = Str::slug($identifier);
                $mou = $query->get()->first(function ($candidate) use ($slug) {
                    return $candidate->public_slug === $slug;
                });
            }
        }

        abort_unless($mou, 404);

        $localizedTitle = app()->getLocale() === 'am'
            ? ($mou->title_am ?: $mou->title_en)
            : ($mou->title_en ?: $mou->title_am);

        $breadcrumbItems = [
            ['label' => __('public.navigation.partnerships'), 'url' => route('public.mous.index')],
            ['label' => __('mous.public.title'), 'url' => route('public.mous.index')],
            [
                'label' => $localizedTitle,
                'url' => route('public.mous.show', ['identifier' => $mou->public_slug]),
            ],
        ];

        return view('mous.show', compact('mou', 'breadcrumbItems'));
    }
}
