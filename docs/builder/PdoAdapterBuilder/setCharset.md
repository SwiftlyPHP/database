---
since: 1.0
type: method
tags: [utility, configuration]
---
# PdoAdapterBuilder::setCharset

Sets the character set to use for the database connection.

You must make sure your chosen driver supports the character set specified.
Failure to do so may result in error, unexpected behaviour or potential data
corruption.

See the [documentation for your chosen driver](https://www.php.net/manual/en/pdo.drivers.php)
for more details.

## Method Signature

```php
public function setCharset(?string $charset): self;
```

## Parameters

* `$charset` - A supported charset name

## Usage
### Using the UTF8-MB4 Set

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Mysql()
    ->setHostname('localhost')
    ->setCharset('utf8mb4')
    ->create();

$database = new Database($adapter);
$database->query(...);
```

## See Also

* [`PdoAdapterBuilder::setDatabase`](./setDatabase.md) - To set the name of the database to use
* [`PdoAdapterBuilder::setOption`](./setOption.md) - To set driver specific options
* [`PdoAdapterBuilder::setAttribute`](./setAttribute.md) - To set PDO specific attributes
