---
since: 1.0
type: method
tags: [query, database]
---
# Query::setDatabase

Associate a query with a given database.

Passing `null` will reset the query, unlinking it from any database.

## Method Signature

```php
public function setDatabase(?Database $database): void;
```

## Parameters

* `$database` - The database the query should run against

## Usage
### Setting the Database

```php
use Swiftly\Database\Database;
use Swiftly\Database\Query;

$database = new Database(...);

$query = new Query('SELECT id, email FROM Customers');
$query->setDatabase($database);

$customers = $query->execute();
```

## See Also

* [`Database::query`] - To create a query associated with an existing database
* [`Database::execute`] - To execute a query directly
* [`Query::getDatabase`] - To get the database which will run the query