<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Translation\TranslationService;
use App\Services\Translation\TranslationServiceInterface;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
        TranslationServiceInterface::class,
        TranslationService::class
        );   
       
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
