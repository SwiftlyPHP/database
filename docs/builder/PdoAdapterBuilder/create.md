---
since: 1.0
type: method
tags: [builder, utility]
---
# PdoAdapterBuilder::create

Creates a new [PdoAdapter](../../backend/PdoAdapter) instance using the
configuration of the current builder.

Can be called as few or many times as required, each time returning a new/fresh
adapter instance. Configuration can be changed between each call,
allowing adapters with similar - but not identical - configuration to be quickly
created in sequence.

See [usage](#usage) for examples.

## Method Signature

```php
public function create(): PdoAdapter;
```

## Return Value

A configured [PdoAdapter](../../backend/PdoAdapter) instance.

## Usage
### Creating a MySQL Adapter

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Mysql()
    ->setHostname('localhost')
    ->create();

$database = new Database($adapter);
$database->query(...);
```

Above we've specified the type (MySQL) and host (localhost) of our database.
Then, when we're ready to start making queries we simply call `create` on the
builder and a configured adapter is prepared for us.

### Multiple Adapters with Different Credentials

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$builder = PdoAdapterBuilder::Mysql()
    ->setHostname('192.168.0.42')
    ->setPort(3306)
    ->setCharset('utf8mb4');

$adminAdapter = $builder
    ->setUsername('admin')
    ->setPassword('p@ssword!');

$readonlyAdapter = $builder
    ->setUsername('readonly')
    ->setPassword('password');

$adminDb = new Database($adminAdapter);
$readonlyDb = new Database($readonlyAdapter);
```

Here we create two adapters. While both are connected to a remote MySQL server
located at `192.168.0.42` and using the `utf8mb4` charset, they are
differentiated by the credentials they use for their connections.

This showcases how the builder might be used to share common configuration
values.

## See Also

* [`PdoAdapter`](../../backend/PdoAdapter) - To learn how to use the created adapter
* [`PdoAdapterBuilder`](./index.md) - To see all supported configuration values
* [`PdoAdapterBuilder::Mysql`](./Mysql.md) - To create builders for MySQL databases
* [`PdoAdapterBuilder::Postgres`](./Postgres.md) - To create builders for PostgreSQL databases