<?php

namespace Chipslays\Queue;

/**
 * @property string $id
 * @property string $channel
 * @property mixed $data
 */
class QueueItem
{
    private array $item;

    /**
     * @param array $item
     */
    public function __construct(array $item)
    {
        $this->item = $item;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->item['id'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getChannel(): ?string
    {
        return $this->item['channel'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return $this->item['data'] ?? null;
    }

    public function __get($name)
    {
        if (!isset($this->item[$name])) {
            return null;
        }

        return $this->item[$name];
    }
}