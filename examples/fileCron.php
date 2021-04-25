<?php

use Chipslays\Queue\Queue;
use Chipslays\Queue\Drivers\File;

require __DIR__ . '/../vendor/autoload.php';

$driver = new File([
    'storage' => __DIR__ . '/storage/',
]);

$queue = new Queue($driver);

if (!$item = $queue->next('payment')) {
    exit;
}

print_r($data);