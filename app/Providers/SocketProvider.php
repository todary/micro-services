<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Libraries\SocketContainer;

class SocketProvider extends ServiceProvider
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
        $this->app->singleton('Socket', function ($app) {
            $socketClient = new \Redis(); // Using the Socket extension provided client
            if (!@$socketClient->connect(env('SOCKET_HOST'), env('SOCKET_PORT'), 2.5)) {
                return null;
            }

            return new SocketContainer($socketClient);
        });
    }
}
