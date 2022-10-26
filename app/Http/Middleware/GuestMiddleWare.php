<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class GuestMiddleWare
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
        if (Auth::user()->hasRole('system') ||
            Auth::user()->hasRole('sale') ||
            Auth::user()->hasRole('tpkd') ||
            Auth::user()->hasRole('adminsale') ||
            Auth::user()->hasRole('boss') ||
            Auth::user()->hasRole('mkt') ||
            Auth::user()->hasRole('hcns'))
            return $next($request);
        else abort(403);
    }
}
