<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsSystemOrIt
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
        if (Auth::guard('admin')->check() && (Auth::user()->systemAdmin() || Auth::user()->ItAdmin())) {
            return $next($request);
        }
        return redirect('/admin')->with('toast-error', __('Not Authorised'));
    }
}
