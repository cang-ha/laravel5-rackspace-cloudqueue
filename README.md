# Laravel 5 Rackspace CloudQueue Driver

##Installation

### Install via Composer
Require this package in your composer.json and run composer update:

"faulker/laravel5-rackspace-cloudqueue": "~1.0"

### Add Configuration

```PHP
/// config/queue.php

return array(
    'default'     => 'rackspace',

    'connections' => array(

        'rackspace'  => [
            'driver'   => 'rackspace',
            'queue'    => 'default_tube', /// the default queue
            'endpoint' => 'US',  /// US or UK
            'username' => 'SOME_RACKSPACE_USERNAME',
            'apiKey'   => 'SOME_RACKSPACE_API_KEY',
            'region'   => 'ORD', /// THE REGION WHERE THE QUEUE IS SETUP
            'urlType'  => 'internalURL', /// Optional, defaults to internalURL
        ]

    ),

);,

```

### Add Service Provider

```PHP
/// config/app.php

return array(

    'providers'  => array(
        'Faulker\RackspaceCloudQueue\RackspaceCloudQueueServiceProvider'
    ),
);

```
