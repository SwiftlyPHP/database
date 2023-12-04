---
since: 1.0
type: method
tags: [utility, configuration]
---
# PdoAdapterBuilder::setDatabase

Set the name of the database that queries should be made against

## Method Signature

```php
public function setDatabase(?string $database): self;
```

## Parameters

* `$database` - The name of the database

## Usage
### Creating Adapters for Seperate Databases

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$builder = PdoAdapterBuilder::Mysql()
    ->setHostname('localhost')
    ->setUsername('root');

$salesAdapter = $builder
    ->setDatabase('sales')
    ->create();

$customerAdapter = $builder
    ->setDatabase('customers')
    ->create();

$salesDb = new Database($salesAdapter);
$customerDb = new Database($customerAdapter);
```

Above we assume a MySQL server running locally. First, we create `$salesAdapter`
which is configured to operate on the `sales` database. Then, we use the same
builder to create the `$customerAdapter`, targeting the `customers` database.

Using these two adapters (both of which share host and username details) we can
operate on their respective databases.

## See Also

* [`PdoAdapterBuilder::setCharset`](./setCharset.md) - To set the connection character set
* [`PdoAdapterBuilder::setUsername`](./setUsername.md) - To set the username to use for the database
* [`PdoAdapterBuilder::setPassword`](./setPassword.md) - To set the password to use for the database
