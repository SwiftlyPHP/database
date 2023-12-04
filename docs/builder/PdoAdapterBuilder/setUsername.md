---
since: 1.0
type: method
tags: [utility, configuration]
---
# PdoAdapterBuilder::setUsername

Set the username to use when connecting to the database.

## Method Signature

```php
public function setUsername(?string $username): self;
```

## Parameters

* `$username` - The username to use

## Usage
### Connecting with Credentials

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Postgres()
    ->setHostname('localhost')
    ->setUsername('staging')
    ->setPassword('pass')
    ->create();

$database = new Database($adapter);
```

In the example above we create a connection to a local PostgreSQL server using
the username `staging` and password `pass`.

## See Also

* [`PdoAdapterBuilder::setCharset`](./setCharset.md) - To set the connection character set
* [`PdoAdapterBuilder::setDatabase`](./setDatabase.md) - To set the name of the database to use
* [`PdoAdapterBuilder::setPassword`](./setPassword.md) - To set the password to use for the database
