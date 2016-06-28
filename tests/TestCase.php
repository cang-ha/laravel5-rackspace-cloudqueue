<?php

namespace Faulker\RackspaceCloudQueue\Tests;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return ['Faulker\RackspaceCloudQueue\RackspaceCloudQueueServiceProvider'];
    }
}
