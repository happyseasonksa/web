<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckBranchId
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
        if (isset($request->branch_id) && Auth::user()->type !== 0) {
            $branchIds = Auth::user()->assignItems()->pluck('branch_id')->toArray();
            if (!in_array($request->branch_id, $branchIds)) {
                return redirect('/admin')->with('toast-error', 'Invalid branch selected');
            }
        }
        return $next($request);
    }
}
