<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckRestaurantId
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
        if (isset($request->restaurant_id) && Auth::user()->type !== 0) {
            $restaurant = getRestAsAdmin(Auth::user())->where('id', $request->restaurant_id)->first();
            if (!$restaurant) {
                return redirect('/admin')->with('toast-error', 'Invalid restaurant selected');
            }
        }
        return $next($request);
    }
}
