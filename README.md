# 🛒 Queue

Simple implement queue processing in PHP.

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

# Queue

Base class for queue manipulate.

## Methods

### `__construct`

```php
/**
 * @param DriverInterface $driver
 */
public function __construct(DriverInterface $driver);
```

### `add`

```php
/**
 * Add item to queue.
 *
 * @param string $channel
 * @param array $data
 * @param integer $sort
 * @return void
 */
public function add(string $channel, array $data, int $sort = QUEUE_DEFAULT_SORT): void;
```

### `first`

```php
/**
 * Get first item in queue.
 *
 * @param string $channel
 * @return Collection|null
 */
public function first(string $channel = ''): ?Collection;
```

### `next`

```php
/**
 * Get next item in queue.
 *
 * @param string $channel
 * @return array|null
 */
public function next(string $channel = ''): ?array;
```

### `delete`

```php
/**
 * Delete item from queue.
 *
 * @param string|Collection $channel Can pass as result from `first` method.
 * @param string $id
 * @return boolean
 */
public function delete($channel, string $id = null): bool;
```

### `list`

```php
/**
 * Get list of queue items.
 *
 * @param string $channel
 * @return array|null Array of `ids` or null if channel not exists.
 */
public function list(string $channel = ''): ?array;
```

### `count`

```php
/**
 * Get count of items in queue.
 *
 * @param string $channel
 * @return integer
 */
public function count(string $channel = ''): int;
```
