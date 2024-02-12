---
since: 1.0
type: method
tags: [utility, constructor, postgres]
---
# PdoAdapterBuilder::Postgres

Creates a [PdoAdapterBuilder](./index.md) that is pre-configured to create
PostgreSQL database adapters.

## Method Signature

```php
public static function Postgres(): self;
```

## Usage
### Creating a PostgreSQL Adapter

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Postgres()
    ->setHostname('127.0.0.1')
    ->setPort(5432)
    ->setUsername('postgres')
    ->setPassword('password')
    ->create();

$database = new Database($adapter);
$database->query(...);
```

## See Also

* [PdoAdapterBuilder::Mysql](./Mysql.md) - To create builders for MySQL databases
* [PdoAdapterBuilder::__construct](./construct.md) - To create builders for any database type
* [PdoAdapter] - More information on the PDO adapter