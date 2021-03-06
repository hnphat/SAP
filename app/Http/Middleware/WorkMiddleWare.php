<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class WorkMiddleWare
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
            Auth::user()->hasRole('watch') ||
            Auth::user()->hasRole('boss') ||
            Auth::user()->hasRole('work'))
            return $next($request);
        else abort(403);
    }
}
