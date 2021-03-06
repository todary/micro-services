<?php

namespace Skopenow\UrlInfo;

use Illuminate\Support\ServiceProvider;

class UrlInfoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__ . '/../vendor/autoload.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        include __DIR__ . '/routes/web.php';
        $app->make('Skopenow\UrlInfo\UrlInfoController');

        $app->singleton('urlinfo', function()
        {
            return new EntryPoint;
        });
    }
}
