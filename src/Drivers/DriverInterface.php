<?php

namespace Chipslays\Queue\Drivers;

use Chipslays\Collection\Collection;
use Chipslays\Queue\QueueItem;

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
     * Returns the `id` of the added item.
     *
     * @param string $channel
     * @param array $data
     * @param int $sort
     * @return string
     */
    public function add(string $channel, array $data, int $sort = QUEUE_DEFAULT_SORT): string;

    /**
     * Get item by ID.
     *
     * @param string $channel
     * @param string $id
     * @return QueueItem|null
     */
    public function get(string $channel, string $id): ?QueueItem;

    /**
     * Get next item in queue.
     *
     * @param string $channel
     * @return QueueItem|null
     */
    public function next(string $channel): ?QueueItem;

    /**
     * Get first item in queue.
     *
     * If queue is empty or `channel` not exists returns `null`.
     *
     * @param string $channel
     * @return QueueItem|null
     */
    public function first(string $channel): ?QueueItem;

    /**
     * Delete item from queue.
     *
     * Returns `true` on success delete and `false` on fail.
     *
     * @param string|QueueItem $channel e.g. Can be passed as result from `first` method.
     * @param string $id
     * @return boolean
     */
    public function delete($channel, string $id = null): bool;

    /**
     * Get all items IDs from queue.
     *
     * Returns array of `id's`, if `channel` not exists returns `null`.
     *
     * @param string $channel
     * @return array|null
     */
    public function list(string $channel): ?array;

    /**
     * Get count of items in queue.
     *
     * Returns count, if `channel` not exists returns 0.
     *
     * @param string $channel
     * @return int
     */
    public function count(string $channel): int;

    /**
     * Get item position in queue.
     *
     * Return position, if `channel` or `id` not exists returns 0.
     *
     * @param string $channel
     * @param string $id
     * @return int
     */
    public function position(string $channel, string $id): int;
}
