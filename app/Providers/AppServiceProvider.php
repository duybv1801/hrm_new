<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ImageService;
use App\Services\ImageServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ImageServiceInterface::class, ImageService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
