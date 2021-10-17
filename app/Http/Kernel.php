<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'login' => \App\Http\Middleware\LoginMiddleWare::class,
        'f_cancel' => \App\Http\Middleware\CancelMiddleWare::class,
        'f_capxang' => \App\Http\Middleware\CapXangMiddleWare::class,
        'f_denghi' => \App\Http\Middleware\DeNghiMiddleWare::class,
        'f_duyet' => \App\Http\Middleware\DuyetMiddleWare::class,
        'f_guest' => \App\Http\Middleware\GuestMiddleWare::class,
        'f_hd' => \App\Http\Middleware\HDMiddleWare::class,
        'f_hoso' => \App\Http\Middleware\HoSoMiddleWare::class,
        'f_kho' => \App\Http\Middleware\KhoMiddleWare::class,
        'f_laithu' => \App\Http\Middleware\LaiThuMiddleWare::class,
        'f_package' => \App\Http\Middleware\PackageMiddleWare::class,
        'f_pheduyet' => \App\Http\Middleware\PheDuyetMiddleWare::class,
        'f_role' => \App\Http\Middleware\RoleMiddleWare::class,
        'f_roleuser' => \App\Http\Middleware\RoleUserMiddleWare::class,
        'f_typecar' => \App\Http\Middleware\TypeCarMiddleWare::class,
        'f_report' => \App\Http\Middleware\ReportMiddleWare::class,
    ];
}
