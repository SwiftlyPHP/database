---
since: 1.0
type: method
tags: [utility, configuration]
---
# PdoAdapterBuilder::setPort

Sets the network port at which the database can be contacted.

## Method Signature

```php
public function setPort(?int $port): self;
```

## Parameters

* `$port` - The port number the database server is listening on

## Usage
### Setting the Port

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Mysql()
    ->setHostname('localhost')
    ->setPort(3308)
    ->create();

$database = new Database($adapter);
```

The above creates a new [PdoAdapter] connected to a local MySQL database that is
listening on the (non-standard) port of **3308**.

## See Also

* [`PdoAdapterBuilder::setHostname`](./setHostname.md) - To set the hostname of the database
* [`PdoAdapterBuilder::setUsername`](./setUsername.md) - To set the username to use for the database
* [`PdoAdapterBuilder::setPassword`](./setPassword.md) - To set the password to use for the database
