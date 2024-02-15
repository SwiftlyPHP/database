---
since: 1.0
type: class
tags: [query, sql, utility]
---
# Query

...

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

* [`Query::__construct`](./construct.md) - 
* [`Query::setParameter`](./setParameter.md) - 
* [`Query::getParameters`](./getParameters.md) - 
* [`Query::hasParameters`](./hasParameters.md) - 
* [`Query::getQuery`](./getQuery.md) - 
* [`Query::execute`](./execute.md) - 

## Usage



## See Also

* [Database] - More information on the database manager