<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BaoCaoHopDongMiddleware
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
         Auth::user()->hasRole('baocaohopdong') ||
         Auth::user()->hasRole('sale') ||
         Auth::user()->hasRole('truongnhomsale') ||
         Auth::user()->hasRole('tpkd'))
            return $next($request);
        else abort(403);
    }
}
