<?php

namespace FuriousDeveloper\LaraBit;

use Illuminate\Support\ServiceProvider;

class LaraBitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadViewsFrom(__DIR__ . DIRECTORY_SEPARATOR . 'views', 'larabit');

        $this->publishes([
            __DIR__ . DIRECTORY_SEPARATOR . 'views' => resource_path('views/vendor/larabit')
        ]);

        if ($this->app->runningInConsole()) {

            $this->commands([LaraBit::class]);
        }
    }
}
