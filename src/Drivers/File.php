<?php

namespace Chipslays\Queue\Drivers;

use Chipslays\Collection\Collection;
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

    public function get(string $channel, string $id): ?array
    {
        if (!file_exists($this->getPath($channel))) {
            return null;
        }

        return unserialize(file_get_contents($this->getPath($channel) . '/' . $id));
    }

    public function next(string $channel): ?array
    {
        $item = $this->first($channel);

        if (!$item) {
            return null;
        }

        $this->delete($item);

        return $item->get('data');
    }

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
