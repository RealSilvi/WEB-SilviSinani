<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Locale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (in_array($request->segment(1), config('app.available_locales'))) {
            app()->setLocale($request->segment(1));
        } else {
            app()->setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
