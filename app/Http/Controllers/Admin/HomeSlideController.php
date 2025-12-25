<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\HomeSlides\CreateHomeSlideAction;
use App\Actions\HomeSlides\DeleteHomeSlideAction;
use App\Actions\HomeSlides\UpdateHomeSlideAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHomeSlideRequest;
use App\Http\Requests\UpdateHomeSlideRequest;
use App\Models\HomeSlide;

final class HomeSlideController extends Controller
{
    public function index()
    {
        $slides = HomeSlide::query()->ordered()->get();

        return view('admin.home-slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.home-slides.create');
    }

    public function store(StoreHomeSlideRequest $request, CreateHomeSlideAction $action)
    {
        $action->execute(
            $request->safe()->except('image'),
            $request->file('image')
        );

        return redirect()->route('admin.home-slides.index')
            ->with('success', 'Slide created.');
    }

    public function edit(HomeSlide $home_slide)
    {
        return view('admin.home-slides.edit', ['slide' => $home_slide]);
    }

    public function update(UpdateHomeSlideRequest $request, HomeSlide $home_slide, UpdateHomeSlideAction $action)
    {
        $action->execute(
            $home_slide,
            $request->safe()->except('image'),
            $request->file('image')
        );

        return redirect()->route('admin.home-slides.index')
            ->with('success', 'Slide updated.');
    }

    public function destroy(HomeSlide $home_slide, DeleteHomeSlideAction $action)
    {
        $action->execute($home_slide);

        return redirect()->route('admin.home-slides.index')
            ->with('success', 'Slide deleted.');
    }
}
