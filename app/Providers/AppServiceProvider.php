<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        try {
            $json = @file_get_contents(public_path('upload/cauhinh/app.json'));
            $appData = $json ? json_decode($json, true) : [];
        } catch (\Exception $e) {
            $appData = [];
        }
        view()->share('data', $appData);
    }
}
