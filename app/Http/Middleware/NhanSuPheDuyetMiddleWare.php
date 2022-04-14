<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class NhanSuPheDuyetMiddleWare
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
            Auth::user()->hasRole('hcns') ||
            Auth::user()->hasRole('lead') ||
            Auth::user()->hasRole('lead_chamcong'))
            return $next($request);
        else abort(403);
    }
}
