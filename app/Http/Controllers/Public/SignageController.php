<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SignageDisplay;

final class SignageController extends Controller
{
    public function show(SignageDisplay $signage_display)
    {
        $signage_display->load('template');

        if (! $signage_display->is_published || ! ($signage_display->template?->is_active)) {
            abort(404);
        }

        return view('public.signage.show', [
            'display' => $signage_display,
            'template' => $signage_display->template,
        ]);
    }
}
