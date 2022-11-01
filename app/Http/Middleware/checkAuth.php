<?php

namespace App\Http\Middleware;

use Closure;

class checkAuth
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
        if ($_GET['request_id']){
            if ($_GET['request_id'] == 404){
                print_r('<center><h2>404 Page</h2></center>');
                die();
            }
        }
        return $next($request);
    }
}
