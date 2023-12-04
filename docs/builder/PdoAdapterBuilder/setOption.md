---
since: 1.0
type: method
tags: [utility, configuration]
---
# PdoAdapterBuilder::setOption

Sets an option that will be passed to [`PDO::__construct`](https://www.php.net/manual/en/pdo.construct.php).

This can be useful to set driver specific options such as
`MYSQL_ATTR_USE_BUFFERED_QUERY`, which you otherwise would not be able to
change after the adapter has been created.

See the [documentation for your driver](https://www.php.net/manual/en/pdo.drivers.php)
for a list of supported options.

## Method Signature

```php
public function setOption(string $option, mixed $value): self;
```

## Parameters

* `$option` - The name/key of the driver option you wish to set
* `$value` - Desired option value

## Usage
### Disabling MySQL Buffered Mode

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Mysql()
    ->setHostname('localhost')
    ->setOption(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false)
    ->create();

$database = new Database($adapter);
$database->query(...);
```

Using the `setOption` method the example above creates a connection to a local
MySQL server and disables buffered queries.

## See Also

* [`PdoAdapterBuilder::setCharset`](./setCharset.md) - To set the connection character set
* [`PdoAdapterBuilder::setAttribute`](./setAttribute.md) - To set PDO specific attributes
