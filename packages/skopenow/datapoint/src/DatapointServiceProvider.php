<?php
/**
 * Datapoint service Provider
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Datapoint;

use Illuminate\Support\ServiceProvider;

/**
 * Datapoint service provider
 *
 * @category Micro_Services-phase_1
 * @package  Datapoint
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class DatapointServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        include __DIR__ . '/../routes/web.php';
        $app->make('Skopenow\Datapoint\DatapointController');

        /*$app->singleton('datapoint', function()
        {
            return new EntryPoint;
        });*/
        $app->singleton('progress_default_data', function () {
            return include __DIR__ . '/../resources/progress_default_data.php';
        });
    }
}
