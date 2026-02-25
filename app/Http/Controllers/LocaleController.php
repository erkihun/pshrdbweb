<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (! in_array($locale, ['am', 'en'], true)) {
            abort(404);
        }

        session()->put('locale', $locale);

        $redirectTo = (string) $request->input('redirect_to');
        $baseUrl = url('/');

        if ($redirectTo !== '' && str_starts_with($redirectTo, $baseUrl)) {
            return redirect()->to($redirectTo);
        }

        return redirect()->back();
    }
}
