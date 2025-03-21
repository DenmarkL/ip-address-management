<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

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
        //
        Cookie::macro('create', function ($name, $value, $minutes) {
            return new SymfonyCookie($name, $value, now()->addMinutes($minutes), '/', null, false, true, false, 'Lax');
        });
    }
}
