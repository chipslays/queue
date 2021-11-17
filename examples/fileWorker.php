<?php

use Chipslays\Queue\Queue;
use Chipslays\Queue\Drivers\File;

require __DIR__ . '/../vendor/autoload.php';

$driver = new File([
    'storage' => __DIR__ . '/storage/',
]);

$queue = new Queue($driver);

while (true) {
    if (!$item = $queue->next('payment')) {
        continue;
    }

    echo 'channel: ' . $item->getChannel() . '/' . $item->channel . PHP_EOL;
    echo 'id: ' . $item->getId() . '/' . $item->id . PHP_EOL;
    echo 'data: ' . print_r($item->getData(), true) . '/' . print_r($item->data, true) . PHP_EOL;
}