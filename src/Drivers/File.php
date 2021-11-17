<?php

namespace Chipslays\Queue\Drivers;

use Chipslays\Queue\QueueItem;
use FilesystemIterator;

class File implements DriverInterface
{
    use DriverTrait;

    public function add(string $channel, array $data, int $sort = QUEUE_DEFAULT_SORT): string
    {
        $path = $this->getPath($channel);

        if (!file_exists($path)) {
            mkdir($path, 655, true);
        }

        [$m, $s] = explode(' ', microtime());
        $id = "{$sort}_{$s}_{$m}";

        file_put_contents($path . '/' . $id, serialize($data));

        return $id;
    }

    public function get(string $channel, string $id): ?QueueItem
    {
        if (!file_exists($this->getPath($channel))) {
            return null;
        }

        return new QueueItem([
            'channel' => $channel,
            'id' => $id,
            'data' => unserialize(file_get_contents($this->getPath($channel) . '/' . $id))
        ]);
    }

    public function next(string $channel): ?QueueItem
    {
        $item = $this->first($channel);

        if (!$item) {
            return null;
        }

        $this->delete($item);

        return $item;
    }

    public function delete($channel, string $id = null): bool
    {
        if ($channel instanceof QueueItem) {
            $path = $this->getPath($channel->channel);
            $id = $channel->id;
        } else {
            $path = $this->getPath($channel);
        }

        return unlink($path . '/' . $id);
    }

    public function list(string $channel): ?array
    {
        $path = $this->getPath($channel);

        if (!file_exists($path)) {
            return null;
        }

        return array_map('basename', glob($path . '/*'));
    }

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
