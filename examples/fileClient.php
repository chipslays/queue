<?php

use Chipslays\Queue\Queue;
use Chipslays\Queue\Drivers\File;

require __DIR__ . '/../vendor/autoload.php';

$driver = new File([
    'storage' => __DIR__ . '/storage/',
]);

$queue = new Queue($driver);
$queue->add('payment', ['id' => 1, 'amount' => 10]);
$queue->add('payment', ['id' => 2, 'amount' => 10]);
$queue->add('payment', ['id' => 3, 'amount' => 10]);
$queue->add('payment', ['id' => 4, 'amount' => 10]);
$queue->add('payment', ['id' => 5, 'amount' => 10]);
// print_r($queue->next('payment'));


