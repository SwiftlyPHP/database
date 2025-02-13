---
since: 1.0
type: class
tags: [database, sql, mysql, postgres]
---
# Database

Provides a wrapper for managing and querying a single database.

## Class Synopsis

```php
namespace Swiftly\Database;

class Database
{
    public const TRANSACTION_ABORT = false;

    public function __construct(AdapterInterface $adapter);
    public function execute(Query $query): ?Collection;
    public function withTransaction(callable $callback): mixed;
    public function hasTransactionSupport(): bool;
    public function query(string $sql): Query;
}
```

## Methods

* [`Database::TRANSACTION_ABORT`](./TRANSACTION_ABORT) - 
* [`Database::__construct`](./construct) - Creates a database wrapper
* [`Database::execute`](./execute) - 
* [`Database::withTransaction`](./withTransaction) - 
* [`Database::hasTransactionSupport`](./hasTransactionSupport) - Check if the adapter supports transactions
* [`Database::query`](./query) - 

## Usage
### Querying a Database

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Mysql()
    ->setHostname('localhost')
    ->setUsername('root')
    ->setPassword('password')
    ->create();

$database = new Database($adapter);
$database
    ->query('SELECT * FROM Users')
    ->execute();
```

Creating a new database manager for a MySQL database and performing a simple
query.

### Using Transactions

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Postgres()
    ->setHostname('127.0.0.1')
    ->setUsername('postgres')
    ->setPassword('password')
    ->create();

$database = new Database($adapter);
$database->withTransaction(function (Database $database): void {
    $database
        ->query('INSERT INTO Product (name, price) VALUES (:name, :price)')
        ->setParameter('name', 'Foo')
        ->setParameter('price', 18.84)
        ->execute();

    $database
        ->query('INSERT INTO Product (name, price) VALUES (:name, :price)')
        ->setParameter('name', 'Bar')
        ->setParameter('price', 9.99)
        ->execute();

    $database
        ->query('INSERT INTO Product (name, price) VALUES (:name, :price)')
        ->setParameter('name', 'Baz')
        ->setParameter('price', 42.00)
        ->execute();
});
```

Example that inserts 3 entries into a `Product` table using a transaction. If
a failure occurs at any point the transaction will be aborted, causing none of
the entries to be persisted.

For more advanced use cases see the [`withTransaction`](./withTransaction)
documentation.

## See Also

- [`PdoAdapter`](../adapter/PdoAdapter) - The preferred database adapter
- [`PdoAdapterBuilder`](../builder/PdoAdapterBuilder) - To simplify the adapter creation process