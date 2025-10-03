<?php

namespace App\Providers;

use App\Classes\XssClean;
use App\Classes\LdapAdapter;
use App\Interface\LdapInterface;
use App\Interface\SanitizerInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
               $this->app->bind(SanitizerInterface::class,XssClean::class);
              $this->app->bind(LdapInterface::class,LdapAdapter::class);
        // $this->app->register(L5SwaggerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::pattern('id', '[0-9]+');
    }
}
