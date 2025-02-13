---
since: 1.0
type: method
tags: [database, constructor]
---
# Database::\_\_construct

Creates a new [Database](./) manager using the given adapter.

## Method Signature

```php
public function __construct(AdapterInterface $adapter);
```

## Parameters

* `$adapter` - The underlying database [adapter](../backend/) you wish to use

## Usage
### Creating a New Database Manager

```php
use Swiftly\Database\Adapter\PdoAdapter;
use Swiftly\Database\Database;

$adapter = new PdoAdapter(
    new PDO("mysql:host=127.0.0.1;port=3306")
);

$database = new Database($adapter);
$database->query(...);
```

## See Also

- [`PdoAdapter`](../adapter/PdoAdapter) - The preferred database adapter
- [`PdoAdapterBuilder`](../builder/PdoAdapterBuilder) - To simplify the adapter creation process

