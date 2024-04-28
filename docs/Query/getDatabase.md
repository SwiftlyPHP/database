---
since: 1.0
type: method
tags: [query, database]
---
# Query::getDatabase

Return the database against which this query will be executed.

## Method Signature

```php
public function getDatabase(): ?Database;
```

## Return Value

The associated [Database](../Database) instance or `null` if not set.

## See Also

* [`Database::query`] - To create a query associated with an existing database
* [`Database::execute`] - To execute a query directly
* [`Database::setDatabase`] - To set the database which will run the query