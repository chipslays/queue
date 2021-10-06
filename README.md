# 🛒 Queue

![Packagist Version](https://img.shields.io/packagist/v/chipslays/queue)
![GitHub](https://img.shields.io/github/license/chipslays/queue)

Simple implement queue processing in PHP.

# Installation

```bash
$ composer require chipslays/queue
```

# Usage

### Client

We push something to queue.

```php
use Chipslays\Queue\Queue;
use Chipslays\Queue\Drivers\File;

require __DIR__ . '/vendor/autoload.php';

$driver = new File([
    'storage' => __DIR__ . '/storage/',
]);

$queue = new Queue($driver);
$queue->add('payment', ['user_id' => 1, 'amount' => 10]);
```

### Worker

We have worker, who get value from queue and starts processing.

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
    //     [user_id] => 1
    //     [amount] => 10
    // )
}
```

### Cron

Or instead worker, we have a cron job.

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
//     [user_id] => 1
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

**Flat File (FileSystem) driver:**

```php
use Chipslays\Queue\Queue;
use Chipslays\Queue\Drivers\File;

require __DIR__ . '/vendor/autoload.php';

$driver = new File([
    'storage' => __DIR__ . '/storage/',
]);

$queue = new Queue($driver);
```

### `add`

```php
/**
 * Add item to queue.
 *
 * Returns the `id` of the added item.
 *
 * @param string $channel
 * @param array $data
 * @param integer $sort
 * @return string
 */
public function add(string $channel, array $data, int $sort = QUEUE_DEFAULT_SORT): string;
```

Example:

```php
use Chipslays\Queue\Queue;

$queue = new Queue($driver);
$id = $queue->add('payment', ['key' => 'value']);
echo $queue->position('payment', $id); // e.g. 1
```

### `first`

```php
/**
 * Get first item in queue.
 *
 * Returns `Collection` of array with `channel`, `id`, `data`,
 * if queue is empty or `channel` not exists returns `null`.
 *
 * @param string $channel
 * @return Collection|null
 */
public function first(string $channel): ?Collection;
```

Example:

```php
use Chipslays\Queue\Queue;

$queue = new Queue($driver);
$item = $queue->first('payment');

if (!$item) {
    return;
}

echo $item->channel . PHP_EOL;
echo $item->id . PHP_EOL;
print_r($item->data);
```

### `next`

```php
/**
 * Get next item in queue.
 *
 * Returns array of data, if queue is empty returns `null`.
 *
 * @param string $channel
 * @return array|null
 */
public function next(string $channel): ?array;
```

Example:

```php
use Chipslays\Queue\Queue;

$queue = new Queue($driver);

// somewhere in client code...
$queue->add('payment', ['currency' => 'EUR', 'amount' => 10]);

// somewhere in worker/cron code...
if (!$data = $queue->next('payment')) {
    return;
}

print_r($data);

// Array
// (
//     [currency] => EUR
//     [amount] => 10
// )
```

### `delete`

```php
/**
 * Delete item from queue.
 *
 * Returns `true` on success delete and `false` on fail.
 *
 * @param string|Collection $channel Can pass as result from `first` method.
 * @param string $id
 * @return boolean
 */
public function delete($channel, string $id = null): bool;
```

Example:

```php
use Chipslays\Queue\Queue;

$queue = new Queue($driver);
$item = $queue->first('payment');

if (!$item) {
    return;
}

// Delete by pass received item from `first` method.
$queue->delete($item);

// Delete by `channel` and `id`.
$queue->delete($item->channel, $item->id);
```

### `list`

```php
/**
 * Get list of queue items.
 *
 * Returns array of `id's`, if `channel` not exists returns `null`.
 *
 * @param string $channel
 * @return array|null
 */
public function list(string $channel): ?array;
```

Example:
```php
use Chipslays\Queue\Queue;

$queue = new Queue($driver);
print_r($queue->list('payment'));

// Array
//
//     [0] => someId__1
//     [1] => someId__2
//     [2] => someId__3
// )
```

> **NOTE:** For each driver, the name of the `id` may be different.

### `count`

```php
/**
 * Get count of items in queue.
 *
 * Returns count, if `channel` not exists returns 0.
 *
 * @param string $channel
 * @return integer
 */
public function count(string $channel): int;
```

Example:
```php
use Chipslays\Queue\Queue;

$queue = new Queue($driver);
echo $queue->count('payment'); // e.g. 32
```

### `position`

```php
/**
 * Get item position in queue.
 *
 * Return position, if `channel` or `id` not exists returns 0.
 *
 * @param string $channel
 * @param string $id
 * @return int
 */
public function position(string $channel, string $id): int;
```

Example:
```php
use Chipslays\Queue\Queue;

$queue = new Queue($driver);
$id = $queue->add('payment', ['key' => 'value']);
echo $queue->position('payment', $id); // e.g. 1
```
