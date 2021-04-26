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
     * Returns the `id` of the added item.
     *
     * @param string $channel
     * @param array $data
     * @param integer $sort
     * @return string
     */
    public function add(string $channel, array $data, int $sort = QUEUE_DEFAULT_SORT): string
    {
        return $this->driver->add($channel, $data, $sort);
    }

    /**
     * Get list of queue items.
     *
     * Returns array of `id's`, if `channel` not exists returns `null`.
     *
     * @param string $channel
     * @return array|null
     */
    public function list(string $channel = ''): ?array
    {
        return $this->driver->list($channel);
    }

    /**
     * Get count of items in queue.
     *
     * Returns count, if `channel` not exists returns 0.
     *
     * @param string $channel
     * @return integer
     */
    public function count(string $channel = ''): int
    {
        return $this->driver->count($channel);
    }

    /**
     * Get next item in queue.
     *
     * Returns array of data, if queue is empty returns `null`.
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
     * Returns `Collection` of array with `channel`, `id`, `data`,
     * if queue is empty or `channel` not exists returns `null`.
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
     * Returns `true` on success delete and `false` on fail.
     *
     * @param string|Collection $channel Can pass as result from `first` method.
     * @param string $id
     * @return boolean
     */
    public function delete($channel, string $id = null): bool
    {
        return $this->driver->delete($channel, $id);
    }

    /**
     * Get item position in queue.
     *
     * Return position, if `channel` or `id` not exists returns 0.
     *
     * @param string $channel
     * @param string $id
     * @return int
     */
    public function position(string $channel, string $id): int
    {
        return $this->driver->position($channel, $id);
    }
}
