---
since: 1.0
type: method
tags: [utility, constructor]
---
# PdoAdapterBuilder::\_\_construct

Creates a [PdoAdapterBuilder](./index.md) that can be used to create adapters
for supported [PDO drivers](https://www.php.net/manual/en/pdo.drivers.php).

## Method Signature

```php
public function __construct(string $type);
```

## Parameters

* `$type` - The name of a PDO driver to use (`mysqli`, `pgsql` etc...)

## Usage
### Creating a MySQL Adapter

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$builder = new PdoAdapterBuilder('mysql');

$adapter = $builder
    ->setHostname('127.0.0.1')
    ->setPort(3306)
    ->setUsername('root')
    ->create();

$database = new Database($adapter);
$database->query(...);
```

## See Also

* [`PdoAdapterBuilder::Postgres`](./Postgres.md) - To create builders for PostgreSQL databases
* [`PdoAdapterBuilder::Mysql`](./Mysql.md) - To create builders for MySQL databases