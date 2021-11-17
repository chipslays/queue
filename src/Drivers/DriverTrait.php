<?php

namespace Chipslays\Queue\Drivers;

use Chipslays\Collection\Collection;
use Chipslays\Queue\QueueItem;

trait DriverTrait
{
    /**
     * @var Collection
     */
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = new Collection($config);
    }

    public function first(string $channel): ?QueueItem
    {
        if ($this->count($channel) === 0) {
            return null;
        }

        $path = $this->getPath($channel);
        $id = scandir($path)[2];

        return new QueueItem([
            'channel' => $channel,
            'id' => $id,
            'data' => unserialize(file_get_contents($path . '/' . $id))
        ]);
    }

    public function position(string $channel, string $id): int
    {
        $items = (array) $this->list($channel);
        $position = array_search($id, $items);

        return $position ? $position + 1 : 0;
    }
}
