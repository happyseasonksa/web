<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class checkStatusAdmin
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
        if (Auth::guard('admin')->check()) {
            if (Auth::user()->status === 0) {
                Auth::logout();
                return redirect('/admin')->with('error', 'Your session has expired because your account is deactivated.');
            }
            return $next($request);
        }
        return redirect('/admin')->with('error', __('Not Authorised'));
    }
}
