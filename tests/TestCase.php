<?php

namespace CangHA\RackspaceCloudQueue\Tests;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return ['CangHA\RackspaceCloudQueue\RackspaceCloudQueueServiceProvider'];
    }
}
