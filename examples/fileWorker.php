<?php

use Chipslays\Queue\Queue;
use Chipslays\Queue\Drivers\File;

require __DIR__ . '/../vendor/autoload.php';

$driver = new File([
    'storage' => __DIR__ . '/storage/',
]);

$queue = new Queue($driver);

while (true) {
    if (!$data = $queue->next('payment')) {
        continue;
    }

    print_r($data);
}