<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsSystemAdmin
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
        if (Auth::guard('admin')->check() && Auth::user()->systemAdmin()) {
            return $next($request);
        }
        return redirect('/admin')->with('toast-error', __('Not Authorised'));
    }
}
