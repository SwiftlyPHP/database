---
since: 1.0
type: method
tags: [utility, configuration]
---
# PdoAdapterBuilder::setPassword

Set the password to use when connecting to the database.

## Method Signature

```php
public function setPassword(?string $password): self;
```

## Parameters

* `$password` - The password to use

## Usage
### Connecting with Credentials

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Mysql()
    ->setHostname('192.168.0.42')
    ->setUsername('root')
    ->setPassword('P@ssword!')
    ->create();

$database = new Database($adapter);
```

In the example above we create a connection to a remote MySQL server using
the username `root` and password `P@ssword!`.

## See Also

* [`PdoAdapterBuilder::setCharset`](./setCharset.md) - To set the connection character set
* [`PdoAdapterBuilder::setDatabase`](./setDatabase.md) - To set the name of the database to use
* [`PdoAdapterBuilder::setUsername`](./setUsername.md) - To set the username to use for the database
