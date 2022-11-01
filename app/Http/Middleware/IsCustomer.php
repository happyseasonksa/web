<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\User;

class IsCustomer
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
        $reqTypeHeader = ($request->hasHeader('Accept')) ? $request->header('Accept') : null;
        $json = ($reqTypeHeader && $reqTypeHeader == 'application/json')?true:false;
        if (Auth::check() && Auth::user() instanceof User) {
            return $next($request);
        }
        if ($json) {
            return response()->json($response = [
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }
        return redirect('/')->with('toast-error', 'Unauthorized.');
    }
}
