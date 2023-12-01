---
since: 1.0
type: method
tags: [utility, configuration]
---
# PdoAdapterBuilder::setFactory

Set a factory method that will be called when a new [PDO](https://www.php.net/manual/en/class.pdo.php)
instance is needed.

By default, the [`PdoAdapterBuilder`](./index.md) constructs new instances of
the [PDO](https://www.php.net/manual/en/class.pdo.php) class whenever the
[`create`](./create.md) method is called. This static method allows that
behaviour to be overridden globally. For example, this can be useful if you
instead want to use a userland class extending from PDO or need to perform some
other specialised setup.

Passing `null` to this method restores the default behaviour.

## Method Signature

```php
public static function setFactory(?callable $factory): void;
```

## Parameters

* `$factory` - A callable factory function that takes the following arguments
    - `string $dsn` - The complete data-source name for the database
    - `?string $username` - The username to use for the database
    - `?string $password` - The password to use for the database
    - `array $options` - Options to pass to [`PDO::__construct`](https://www.php.net/manual/en/pdo.construct.php)

## Usage
### Using a Custom PDO Adapter

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;

class MyPdoWrapper extends PDO
{
    public function __construct()
    {
        echo 'Hi from a custom class!';
    }
}

PdoAdapterBuilder::setFactory(
    function (string $dsn, ?string $username, ?string $password, array $options) {
        return new MyPdoWrapper();
    }
);

// Outputs: 'Hi from a custom class!'
PdoAdapterBuilder::Mysqli()
    ->setHostname('localhost');
    ->create();
```

In the above example we have created the `MyPdoWrapper` class that extends from
PDO. We then update the `PdoAdapterBuilder` with our custom factory function
that - when called - returns an instance of our new class.

Later on in our program we create a new PDO adapter, causing the builder to call
into our factory and use our custom PDO implementation.

### Resetting the Factory

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;

$builder = PdoAdapterBuilder::Postgres()
    ->setHostname('127.0.0.1')
    ->setPort(5432);

$count = 0;

PdoAdapterBuilder::setFactory(
    function ($dsn, $username, $password, $options) use (&$count) {
        $count++;
        return new PDO($dsn, $username, $password, $options);
    }
);

$builder->create(); // $count === 1

PdoAdapterBuilder::setFactory(null);

$builder->create(); // $count === 1
```

Above the factory function is called when `create` is invoked, causing the
counter to increment. Then we reset the factory, clearing the callback and
meaning that on the second call to `create` the counter is left untouched.

## See Also

* [`PdoAdapter`] - The adapter that wraps the PDO instance
* [`PDO`](https://www.php.net/manual/en/intro.pdo.php) - More information on PDO
