<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;


class LanguageController extends Controller
{
    public function change($locale)
    {
        if (!in_array($locale, ['en', 'ar'])) {
            abort(400);
        }

        session(['locale' => $locale]);
        App::setLocale($locale);

        return response()->json([
            'message' => __('text.Language changed successfully'),
            'locale' => $locale,
            'sample_text' => __('text.dashboard_title'),
        ]);
    }
}
