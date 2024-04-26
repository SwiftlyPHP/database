---
since: 1.0
type: class
tags: [query, sql]
---
# Query

Represents a single executable SQL query and any passed parameters.

## Class Synopsis

```php
namespace Swiftly\Database;

class Query implements DatabaseAwareInterface
{
    public function __construct(string $query);
    public function setDatabase(?Database $database): void;
    public function getDatabase(): ?Database;
    public function setParameter(string $name, mixed $value): self;
    public function getParameters(): array;
    public function hasParameters(): bool;
    public function getQuery(): string;
    public function execute(): ?Collection;
}
```

## Methods

* [`Query::__construct`](./construct.md) - Creates a new query with the given SQL
* [`Query::setDatabase`](./setDatabase.md) - Sets the database which will run the query
* [`Query::getDatabase`](./getDatabase.md) - Gets the database which will run the query
* [`Query::setParameter`](./setParameter.md) - 
* [`Query::getParameters`](./getParameters.md) - 
* [`Query::hasParameters`](./hasParameters.md) - 
* [`Query::getQuery`](./getQuery.md) - 
* [`Query::execute`](./execute.md) - 

## Usage
### Creating a Query from an Existing Database

The preferred method of creating a new query is to use the [`query`]() method of
the [Database] class.

```php
use Swiftly\Database\Database;

$database = new Database(...);

$query = $database->query('SELECT * FROM Users');
```

Calling `Database::query` has the added benefit of associating the query with
the database ahead of time, allowing it to be executed immediately.

```php
use Swiftly\Database\Database;

$database = new Database(...);

$result = $database
    ->query('SELECT * FROM Users')
    ->execute();
```

### Creating a Query Manually

Queries can also be created in isolation by instantiating one directly.

```php
use Swiftly\Database\Query;

$query = new Query('SELECT * FROM Users');
```

However, using this method the query has no awareness of the database you wish 
to target. As a result you must either call [`setDatabase`] on the query or pass
the query into [`Database::execute`] yourself.

```php
use Swiftly\Database\Database;
use Swiftly\Database\Query;

$database = new Database(...);

// Pass the database to the query...
$query = new Query('SELECT * FROM Users');
$query->setDatabase($database);
$result = $query->execute();

// ... or pass the query to the database
$query = new Query('SELECT * FROM Users');
$result = $database->execute($query);
```

Trying to execute a query without an associated database will result in a
[`AdapterException`] being thrown.

## See Also

* [Database] - More information on the database manager