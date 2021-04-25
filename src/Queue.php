<?php

namespace Chipslays\Queue;

use Chipslays\Collection\Collection;
use Chipslays\Queue\Drivers\DriverInterface;

define('QUEUE_DEFAULT_SORT', 500);

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

    /**
     * Add item to queue.
     *
     * @param string $channel
     * @param array $data
     * @param integer $sort
     * @return void
     */
    public function add(string $channel, array $data, int $sort = QUEUE_DEFAULT_SORT): void
    {
        $this->driver->add($channel, $data, $sort);
    }

    /**
     * Get list of queue items.
     *
     * @param string $channel
     * @return array|null Array of `ids` or null if channel not exists.
     */
    public function list(string $channel = ''): ?array
    {
        return $this->driver->list($channel);
    }

    /**
     * Get count of items in queue.
     *
     * @param string $channel
     * @return integer
     */
    public function count(string $channel = ''): int
    {
       return $this->driver->count($channel);
    }

    /**
     * Get next item on queue.
     *
     * @param string $channel
     * @return array|null
     */
    public function next(string $channel = ''): ?array
    {
        return $this->driver->next($channel);
    }

    /**
     * Get first item in queue.
     *
     * @param string $channel
     * @return Collection|null
     */
    public function first(string $channel = ''): ?Collection
    {
        return $this->driver->first($channel);
    }

    /**
     * Delete item from queue.
     *
     * @param string|Collection $channel Can pass as result from `first` method.
     * @param string $id
     * @return boolean
     */
    public function delete($channel, string $id = null): bool
    {
        return $this->driver->delete($channel, $id);
    }
}
