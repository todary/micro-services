<?php
/**
 * Search Logger Service Provider
 * 
 * @package  SearchLogger
 * @author   Ahmed Samir <ahmedsamir732@gmail.com>
 **/
namespace SearchLogger;

use Illuminate\Support\ServiceProvider;

class SearchLoggerServiceProvider extends ServiceProvider
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

        include __DIR__ . '/routes/logger.php';
        $app->make('SearchLogger\SearchLoggerController');

    }
}
