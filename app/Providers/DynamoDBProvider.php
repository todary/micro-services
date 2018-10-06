<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Aws\DynamoDb\DynamoDbClient;
use Aws\Credentials\Credentials;
use App\Libraries\DynamoDBContainer;
use App\Libraries\FlexMarchal;

class DynamoDBProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('DynamoDB', function ($app) {

            if (app()->environment('local') || !env('AWS_KEY')) {
                $credentials = new Credentials("local", "local");
            
                $options = array(
                        'credentials' => $credentials,
                        'endpoint' => "http://192.168.1.251:8000",
                        'region'  => 'us-west-2',
                        'version' => '2012-08-10',
                        'retries' => 3,
                );
            } else {
                $credentials = new Credentials(env('AWS_KEY'), env('AWS_SECRET'));

                $options = array(
                        'credentials' => $credentials,
                        'region'  => env('AWS_REGION'),
                        'version' => '2012-08-10',
                        'retries' => 3,
                );
            }

            $client = DynamoDbClient::factory($options);
            
            $marshaler = new FlexMarchal;

            return new DynamoDBContainer($client, $marshaler);
        });
    }
}
