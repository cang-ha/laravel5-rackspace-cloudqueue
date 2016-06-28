<?php

namespace Faulker\RackspaceCloudQueue\Tests;

use Faulker\RackspaceCloudQueue\Queue\RackspaceCloudQueue;
use Mockery as m;

class CloudQueueTest extends TestCase
{

    protected $cloudQueueService;

    public function setUp()
    {
        $this->cloudQueueService = m::mock('OpenCloud\Queues\Service');
        $this->default           = 'default';

    }

    public function testPopProperlyPopsJobOff()
    {
        $message = m::mock('OpenCloud\Queues\Resource\Message');

        $response = m::mock('OpenCloud\Common\Collection\PaginatedIterator')
                     ->shouldReceive(['valid'=>true, 'current'=>$message])
                     ->getMock();

        $openCloudQueue = m::mock('OpenCloud\Queues\Resource\Queue')
                           ->shouldReceive('claimMessages')
                           ->andReturn($response)
                           ->getMock();

        $this->cloudQueueService->shouldReceive('createQueue')->once()->andReturn($openCloudQueue)->getMock();

        $queue = new RackspaceCloudQueue(
            $this->cloudQueueService,
            $this->default
        );
        $queue->setContainer(m::mock('Illuminate\Container\Container'));

        $job = $queue->pop();

        $this->assertInstanceOf('Faulker\RackspaceCloudQueue\Queue\Jobs\RackspaceCloudQueueJob', $job);
    }

    public function testPushProperlyPushesJobOn()
    {
        $job = m::mock('Faulker\RackspaceCloudQueue\Queue\Jobs\RackspaceCloudQueueJob');

        $openCloudQueue = m::mock('OpenCloud\Queues\Resource\Queue')
                           ->shouldReceive('createMessage')
                           ->andReturn(true)
                           ->getMock();

        $this->cloudQueueService->shouldReceive('createQueue')->once()->andReturn($openCloudQueue)->getMock();

        $queue = new RackspaceCloudQueue(
            $this->cloudQueueService,
            $this->default
        );
        $queue->setContainer(m::mock('Illuminate\Container\Container'));

        $this->assertTrue($queue->push($job));

    }


    public function tearDown()
    {
        if ($container = m::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        parent::tearDown();
    }
}
