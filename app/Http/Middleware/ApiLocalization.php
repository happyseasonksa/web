<?php

namespace App\Http\Middleware;

use Closure;

class ApiLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check header request and determine localizaton
        $local = ($request->hasHeader('Accept-Language')) ? $request->header('Accept-Language') : 'en';
        if (!in_array($local, \Config::get('app.supported_languages'))) {
            // respond with error
            return response()->json($response = [
                'success' => false,
                'message' => 'Language not supported.',
            ], 403);
        }
        // set laravel localization
            app()->setLocale($local);
        // continue request
        return $next($request);
    }
}
