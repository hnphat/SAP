<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BHPKMiddleware
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
        Auth::user()->hasRole('nv_baohiem') ||
        Auth::user()->hasRole('nv_phukien') ||
        Auth::user()->hasRole('to_phu_kien') ||
        Auth::user()->hasRole('baocaophukienbaohiem'))
        return $next($request);
    else abort(403);
    }
}
