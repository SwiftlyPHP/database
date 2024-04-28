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

### Passing Query Parameters

To enable queries to be dynamic they also accept parameters. Parameters are
values that will be substituted into the query, replacing placeholders with the
same name.

```php
use Swiftly\Database\Query;

$query = new Query('SELECT id, email FROM Users WHERE username = :username');
$query->setParameter('username', 'John');

// SELECT id, email FROM Users Where username = 'John';
$query->execute();
```

Parameters will then be expanded and escaped before the query is executed by the
database adapter.

In addition to scalar values parameters can also accept an array, allowing you
to easily construct `WHERE...IN` or `INSERT...INTO` statements.

```php
use Swiftly\Database\Query;

$query = new Query('SELECT id, email FROM Users WHERE username IN (:users)');
$query->setParameter('users', ['John', 'Jill', 'Jack']);

// SELECT id, email FROM Users WHERE username IN ('John', 'Jill', 'Jack')
$query->execute();
```

As with scalar parameters, each value in the provided array will be properly
expanded and escaped by the underlying adapter.

### Complete Example

When all the above is taken together querying a database begins to look like the
following.

```php
use Swiftly\Database\Database;

$database = new Database(...);

$users = $database
    ->query('SELECT id, email FROM Users WHERE username IN (:users)')
    ->setParameter('users', ['John', 'Jill', 'Jack'])
    ->execute();
```

## See Also

* [Database] - More information on the database manager