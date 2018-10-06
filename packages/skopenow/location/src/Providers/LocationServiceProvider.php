<?php

namespace Skopenow\Location\Providers;

use Illuminate\Support\ServiceProvider;
use Skopenow\Location\EntryPoint;

class LocationServiceProvider extends ServiceProvider
{
	/**
	 * [$defer property to true means this class will only be loaded when necessary]
	 * 
	 * @var boolean
	 */
	protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;
        include __DIR__.'/../routes.php';
        $this->app->make('Skopenow\Location\LocationController');

        $this->app->singleton('LocationServices', function(){

            return new EntryPoint;

        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        
    }

}
