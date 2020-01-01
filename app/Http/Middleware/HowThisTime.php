<?php

namespace App\Http\Middleware;

use Closure;

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
        $mytime = date('i');

        if($mytime % 2 == 0)
            abort(403, 'Get out!!!');
        
        return $next($request);
    }
}
