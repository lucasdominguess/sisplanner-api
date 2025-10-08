<?php

namespace App\Providers;

use App\Classes\XssClean;
use App\Classes\LdapAdapter;
use App\Enums\Roles;
use App\Interface\LdapInterface;
use Illuminate\Support\Facades\Gate;
use App\Interface\SanitizerInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SanitizerInterface::class, XssClean::class);
        $this->app->bind(LdapInterface::class, LdapAdapter::class);
        // $this->app->register(L5SwaggerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::pattern('id', '[0-9]+');
        // Gate para verificar se o usuário é um Administrador
        Gate::define('Gate-Admin', function ($user) {
            return $user->hasRole(Roles::USER->label());
        });
    }
}
