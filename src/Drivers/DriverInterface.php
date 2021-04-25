<?php

namespace Chipslays\Queue\Drivers;

use Chipslays\Collection\Collection;

interface DriverInterface
{
    /**
     * Accepts config as an array.
     *
     * @param array $config
     */
    public function __construct(array $config = []);

    /**
     * Add item to queue.
     *
     * @param string $channel
     * @param array $data
     * @param integer $sort
     * @return void
     */
    public function add(string $channel, array $data, int $sort = QUEUE_DEFAULT_SORT): void;

    /**
     * Get next item in queue.
     *
     * @param string $channel
     * @return array|null
     */
    public function next(string $channel = ''): ?array;

    /**
     * Get first item in queue.
     *
     * @param string $channel
     * @return Collection|null
     */
    public function first(string $channel = ''): ?Collection;

    /**
     * Delete item from queue.
     *
     * @param string|Collection $channel Can pass as result from `first` method.
     * @param string $id
     * @return boolean
     */
    public function delete($channel, string $id = null): bool;

    /**
     * Get list of queue items.
     *
     * @param string $channel
     * @return array|null Array of `ids` or null if channel not exists.
     */
    public function list(string $channel = ''): ?array;

    /**
     * Get count of items in queue.
     *
     * @param string $channel
     * @return integer
     */
    public function count(string $channel = ''): int;
}
