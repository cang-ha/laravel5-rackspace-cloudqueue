# Laravel 5 Rackspace CloudQueue Driver

> NOTE: This is a fork of Russell Todd's Laravel 5.0 branch (https://github.com/faulker/laravel5-rackspace-cloudqueue)

##Installation

### Install via Composer
Require this package in your composer.json and run composer update:

"CangHA/laravel5-rackspace-cloudqueue": "~1.0"

### Add Configuration

```PHP
// config/queue.php

return array(
    'default'     => 'rackspace',

    'connections' => array(

        'rackspace'  => [
            'driver'   => 'rackspace',
            'queue'    => 'default_tube', // the default queue
            'endpoint' => 'US',  // US or UK
            'username' => 'SOME_RACKSPACE_USERNAME',
            'apiKey'   => 'SOME_RACKSPACE_API_KEY',
            'region'   => 'ORD', // THE REGION WHERE THE QUEUE IS SETUP
            'urlType'  => 'internalURL', // [Optional] (default: internalURL) publicURL if connection from remote network or internalURL if connection from RackSpace network.
        ]

    ),

);,

```

### Add Service Provider (Only for Laravel 5.4 and below)

```PHP
/// config/app.php

return array(

    'providers'  => array(
        'CangHA\RackspaceCloudQueue\RackspaceCloudQueueServiceProvider'
    ),
);

```
