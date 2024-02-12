---
since: 1.0
type: method
tags: [utility, constructor, mysql]
---
# PdoAdapterBuilder::Mysql

Creates a [PdoAdapterBuilder](./index.md) that is pre-configured to create MySQL
database adapters.

## Method Signature

```php
public static function Mysql(): self;
```

## Usage
### Creating a MySQL Adapter

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Mysql()
    ->setHostname('127.0.0.1')
    ->setPort(3306)
    ->setUsername('root')
    ->setPassword('password')
    ->create();

$database = new Database($adapter);
$database->query(...);
```

## See Also

* [PdoAdapterBuilder::Postgres](./Postgres.md) - To create builders for PostgreSQL databases
* [PdoAdapterBuilder::__construct](./construct.md) - To create builders for any database type
* [PdoAdapter] - More information on the PDO adapter