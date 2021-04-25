# 🛒 Queue

todo

# Installation

```bash
$ composer require chipslays/queue
```

# Usage

### Client

```php
use Chipslays\Queue\Queue;
use Chipslays\Queue\Drivers\File;

require __DIR__ . '/vendor/autoload.php';

$driver = new File([
    'storage' => __DIR__ . '/storage/',
]);

$queue = new Queue($driver);
$queue->add('payment', ['id' => 1, 'amount' => 10]);
```

### Worker

```php
use Chipslays\Queue\Queue;
use Chipslays\Queue\Drivers\File;

require __DIR__ . '/vendor/autoload.php';

$driver = new File([
    'storage' => __DIR__ . '/storage/',
]);

$queue = new Queue($driver);

while (true) {
    if (!$data = $queue->next('payment')) {
        continue;
    }

    print_r($data);

    // Array
    // (
    //     [id] => 1
    //     [amount] => 10
    // )
}
```

### Cron

```php
use Chipslays\Queue\Queue;
use Chipslays\Queue\Drivers\File;

require __DIR__ . '/vendor/autoload.php';

$driver = new File([
    'storage' => __DIR__ . '/storage/',
]);

$queue = new Queue($driver);

if (!$item = $queue->next('payment')) {
    exit;
}

print_r($data);

// Array
// (
//     [id] => 1
//     [amount] => 10
// )
```