<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            $settings = \App\Models\OfficeSetting::first() ?? new \App\Models\OfficeSetting();
            \Illuminate\Support\Facades\View::share('settings', $settings);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\View::share('settings', new \App\Models\OfficeSetting());
        }
    }
}
