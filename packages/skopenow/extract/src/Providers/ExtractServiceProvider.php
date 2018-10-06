<?php

/**
 * ExtractServiceProvider
 *
 * PHP version 7
 *
 * @package   Providers
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\Extract\Providers;

use Illuminate\Support\ServiceProvider;
use Skopenow\Extract\EntryPoint;

class ExtractServiceProvider extends ServiceProvider
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
        $this->app->make('Skopenow\Extract\ExtractController');

        $this->app->singleton(
            'ExtractService', function () {

                return new EntryPoint;

            }
        );
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