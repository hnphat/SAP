<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MktMiddleWare
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
            Auth::user()->hasRole('mkt') ||
            Auth::user()->hasRole('tpkd') ||
            Auth::user()->hasRole('cskh') ||
            Auth::user()->hasRole('adminsale') ||
            Auth::user()->hasRole('boss') ||
            Auth::user()->hasRole('truongnhomsale'))
            return $next($request);
        else abort(403);
    }
}
