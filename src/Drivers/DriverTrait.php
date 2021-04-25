<?php

namespace Chipslays\Queue\Drivers;

use Chipslays\Collection\Collection;

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
}
