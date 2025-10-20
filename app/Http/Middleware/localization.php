<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;


class localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('locale')) {
            App::setLocale($request->session()->get('locale'));
        } else {
            App::setLocale(config('app.locale')); // استخدم القيمة الافتراضية من config/app.php
        }
        return $next($request);
    }
}
