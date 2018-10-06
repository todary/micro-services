<?php
/**
 * Relationship service Provider
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Relationship
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Relationship;

use Illuminate\Support\ServiceProvider;

/**
 * Relationship service provider
 *
 * @category Micro_Services-phase_1
 * @package  Relationship
 * @author   Mahmoud AboElnouR <aboelnoor@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
class RelationshipServiceProvider extends ServiceProvider
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
        $app->make('Skopenow\Relationship\RelationshipController');

        /*$app->singleton('relationship', function()
        {
            return new EntryPoint;
        });*/
    }
}
