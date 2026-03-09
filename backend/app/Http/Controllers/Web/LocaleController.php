<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function setLocale(Request $request, string $locale)
    {
        $allowed = ['en', 'am', 'or'];
        if (in_array($locale, $allowed)) {
            session(['locale' => $locale]);
            app()->setLocale($locale);
        }
        return redirect()->back();
    }
}
