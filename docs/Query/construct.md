---
since: 1.0
type: method
tags: [constructor, query, sql]
---
# Query::\_\_construct

Create a new [`Query`](.) object with the given SQL.

## Method Signature

```php
public function __construct(string $query);
```

## Parameters

* `$query` - The SQL for the query

## Usage
### Creating a Query

```php
use Swiftly\Database\Query;

$query = new Query('SELECT * FROM Sales');
```

## See Also

* [`Database::query`] - To create a query against an existing database
* [`Database::execute`] - To execute a query