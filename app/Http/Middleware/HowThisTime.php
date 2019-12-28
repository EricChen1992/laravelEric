<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class HowThisTime
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
        $mytime = Carbon::now();
        // print_r($mytime->minute);
        
        if ($request->age <= 200 && $mytime->minute%2 != 0) {
            // print_r($mytime->minute%2);
            return $next($request);
        } else {
            abort(403, 'Access denied');
        }
        
    }
}
