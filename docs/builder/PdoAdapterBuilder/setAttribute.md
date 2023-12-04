---
since: 1.0
type: method
tags: [utility, configuration]
---
# PdoAdapterBuilder::setAttribute

Sets an attribute value that will be passed to [`PDO::setAttribute`](https://www.php.net/manual/en/pdo.setattribute.php).

## Method Signature

```php
public function setAttribute(int $attribute, mixed $value): self;
```

## Parameters

* `$attribute` - One of the [`PDO::ATTR_*`](https://www.php.net/manual/en/pdo.setattribute.php) flags
* `$value` - Desired attribute value

## Usage
### Setting the Column Case Attribute

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Mysql()
    ->setHostname('localhost')
    ->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER)
    ->create();

$database = new Database($adapter);
$database->query(...);
```

In this example we've used the `setAttribute` method to set the `PDO::ATTR_CASE`
attribute to lowercase.

## See Also

* [`PdoAdapterBuilder::setCharset`](./setCharset.md) - To set the connection character set
* [`PdoAdapterBuilder::setOption`](./setOption.md) - To set driver specific options
