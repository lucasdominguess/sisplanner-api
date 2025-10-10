<?php

namespace App\Providers;

use App\Enums\Roles;

use App\Adapters\LdapAdapter;
use App\Adapters\DomPdfAdapter;
use App\Adapters\XssCleanAdapter;
use App\Interfaces\LdapInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Interfaces\SanitizerInterface;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\PdfExporterInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SanitizerInterface::class, XssCleanAdapter::class);
        $this->app->bind(LdapInterface::class, LdapAdapter::class);
        $this->app->bind(PdfExporterInterface::class, DomPdfAdapter::class);
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
            return $user->hasRole(Roles::ADMIN->label());
        });
    }
}
