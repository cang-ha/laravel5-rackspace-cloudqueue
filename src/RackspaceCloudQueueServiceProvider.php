<?php namespace CangHA\RackspaceCloudQueue;

use CangHA\RackspaceCloudQueue\Queue\Connectors\RackspaceCloudQueueConnector;
use Illuminate\Support\ServiceProvider;
use Queue;

class RackspaceCloudQueueServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booted(function ()
        {
            Queue::extend('rackspace', function ()
            {
                return new RackspaceCloudQueueConnector;
            });
        });
    }
}
