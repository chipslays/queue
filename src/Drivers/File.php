<?php

namespace Chipslays\Queue\Drivers;

use Chipslays\Collection\Collection;
use FilesystemIterator;

class File implements DriverInterface
{
    use DriverTrait;

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
        $path = $this->getPath($channel);

        if (!file_exists($path)) {
            mkdir($path, 655, true);
        }

        [$m, $s] = explode(' ', microtime());
        $id = "{$sort}_{$s}_{$m}.wait";

        file_put_contents($path . '/' . $id, serialize($data));

        return $id;
    }

    /**
     * Get next item in queue.
     *
     * Returns array of data, if queue is empty returns `null`.
     *
     * @param string $channel
     * @return array|null
     */
    public function next(string $channel): ?array
    {
        $item = $this->first($channel);

        if (!$item) {
            return null;
        }

        $this->delete($item);

        return $item->get('data');
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
    public function first(string $channel): ?Collection
    {
        if ($this->count($channel) === 0) {
            return null;
        }

        $path = $this->getPath($channel);
        $id = scandir($path)[2];

        return new Collection([
            'channel' => $channel,
            'id' => $id,
            'data' => unserialize(file_get_contents($path . '/' . $id)),
        ]);
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
        if ($channel instanceof Collection) {
            $path = $this->getPath($channel->get('channel'));
            $id = $channel->get('id');
        } else {
            $path = $this->getPath($channel);
        }

        return unlink($path . '/' . $id);
    }

    /**
     * Get list of queue items.
     *
     * Returns array of `id's`, if `channel` not exists returns `null`.
     *
     * @param string $channel
     * @return array|null
     */
    public function list(string $channel): ?array
    {
        $path = $this->getPath($channel);

        if (!file_exists($path)) {
            return null;
        }

        return array_map('basename', glob($path . '/*.wait'));
    }

    /**
     * Get count of items in queue.
     *
     * Returns count, if `channel` not exists returns 0.
     *
     * @param string $channel
     * @return integer
     */
    public function count(string $channel): int
    {
        $path = $this->getPath($channel);

        if (!file_exists($path)) {
            return 0;
        }

        return iterator_count(
            new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS)
        );
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
        $items = (array) $this->list($channel);
        $position = array_search($id, $items);

        return $position ? $position + 1 : 0;
    }

    /**
     * @param string $channel
     * @return string
     */
    private function getPath(?string $channel): string
    {
        $channel = str_replace(['.', ':'], '/', $channel);
        $storage = rtrim($this->config->get('storage'), '\/');

        return $storage . '/' . $channel;
    }
}
