---
since: 1.0
type: method
tags: [utility, configuration]
---
# PdoAdapterBuilder::setSocket

Sets the UNIX socket at which the database can be contacted.

## Method Signature

```php
public function setSocket(?string $socket): self;
```

## Parameters

* `$socket` - The UNIX socket file of the database server

## Usage
### Setting the UNIX Socket

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Mysql()
    ->setSocket('/var/run/mysqld/mysqld.sock')
    ->create();

$database = new Database($adapter);
```

The above will create a new [PdoAdapter] connected to a MySQL database through
the socket **/var/run/mysqld/mysqld.sock**.

## See Also

* [`PdoAdapterBuilder::setHostname`](./setHostname.md) - To set the hostname of the database
* [`PdoAdapterBuilder::setPort`](./setPort.md) - To set the port used to connect to the database
