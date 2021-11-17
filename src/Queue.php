<?php

namespace Chipslays\Queue;

use Chipslays\Queue\Drivers\DriverInterface;
use Exception;

define('QUEUE_DEFAULT_SORT', 500);

/**
 * @method string add(string $channel, array $data, int $sort = QUEUE_DEFAULT_SORT)
 * @method QueueItem|null get(string $channel, string $id)
 * @method QueueItem|null next(string $channel)
 * @method QueueItem|null first(string $channel)
 * @method boolean delete($channel, string $id = null)
 * @method array|null list(string $channel)
 * @method int count(string $channel)
 * @method int position(string $channel, string $id)
 */
class Queue
{
    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    public function __call($name, $arguments)
    {
        if (!method_exists($this->driver, $name)) {
            throw new Exception("Method {$name} not exists in " . get_class($this->driver), 1);
        }

        return call_user_func_array([$this->driver, $name], $arguments);
    }
}
