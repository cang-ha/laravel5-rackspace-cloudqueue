<?php namespace CangHA\RackspaceCloudQueue\Queue;

use OpenCloud\Common\Constants\Datetime;
use OpenCloud\Queues\Service as OpenCloudService;
use OpenCloud\Queues\Resource\Queue as OpenCloudQueue;
use CangHA\RackspaceCloudQueue\Queue\Jobs\RackspaceCloudQueueJob;
use Illuminate\Queue\Queue;
use Illuminate\Contracts\Queue\Queue as QueueContract;

class RackspaceCloudQueue extends Queue implements QueueContract
{

    /**
     * The Rackspace OpenCloud Message Service instance.
     *
     * @var OpenCloudService
     */
    protected $openCloudService;

    /**
     * The Rackspace OpenCloud Queue instance
     *
     * @var OpenCloudQueue
     */
    protected $queue;

    /**
     * The name of the default tube.
     *
     * @var string
     */
    protected $default;

    public function __construct(OpenCloudService $openCloudService, $default)
    {
        $this->openCloudService = $openCloudService;
        $this->default          = $default;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string $job
     * @param  mixed  $data
     * @param  string $queue
     *
     * @return mixed
     */
    public function push($job, $data = '', $queue = null)
    {
        $this->createQueue($queue);

        return $this->pushRaw($this->createPayload($job, $data), $queue);
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param  string $payload
     * @param  string $queue
     * @param  array  $options
     *
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = array())
    {
        $ttl = array_key_exists('ttl', $options) ? $options['ttl'] : Datetime::DAY * 2;

        return $this->queue->createMessage(array(
            'body' => $payload,
            'ttl'  => $ttl
        ));
    }

    /**
     * Push a new job onto the queue after a delay.
     * Rackspace does not support later method, made it as normal push method
     * 
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        return $this->push($job, $data, $queue);
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param null $queue
     *
     * @return \CangHA\RackspaceCloudQueue\Queue\Jobs\RackspaceCloudQueueJob
     */
    public function pop($queue = null)
    {
        $queue = $this->getQueue($queue);

        $this->createQueue($queue);

        /**
         * @var \OpenCloud\Common\Collection\PaginatedIterator $response
         */
        $response = $this->queue->claimMessages(array(
            //'limit' => 1,
            'grace' => 5 * Datetime::MINUTE,
            'ttl'   => 5 * Datetime::MINUTE
        ));

        if ( $response and $response->valid() )
        {
            $message = $response->current();

            return new RackspaceCloudQueueJob($this->container, $this->queue, $queue, $message);
        }
    }

    /**
     * @param $queue
     *
     * @return \OpenCloud\Queues\Resource\Queue
     */
    private function createQueue($queue)
    {
        if(!empty($queue))
        {
            $this->queue = $this->openCloudService->createQueue($queue);
        }
        else
        {
            $this->queue = $this->openCloudService->createQueue($this->default);
        }

        return $this->queue;
    }

    /**
     * Get the queue or return the default.
     *
     * @param  string|null $queue
     *
     * @return string
     */
    public function getQueue($queue)
    {
        return $queue ?: $this->default;
    }

    /**
     * Get the underlying OpenCloud Queue instance.
     *
     * @return OpenCloudQueue
     */
    public function getOpenCloudQueue()
    {
        return $this->queue;
    }
    
     /**
     * Get the size of the queue.
     *
     * @param  string $queue
     * @return int
     */
    public function size($queue = null)
    {
        $queue = $this->getQueue($queue);

        $response = $queue->getStats();
        return (int) $response->getBody()->messages->total;
    }

}
