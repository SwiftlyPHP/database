---
since: 1.0
type: method
tags: [utility, configuration]
---
# PdoAdapterBuilder::setHostname

Sets the hostname at which the database can be contacted.

## Method Signature

```php
public function setHostname(?string $hostname): self;
```

## Parameters

* `$hostname` - The hostname/IP address of the database server

## Usage
### Setting the Hostname

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Mysql()
    ->setHostname('127.0.0.1')
    ->create();

$database = new Database($adapter);
```

The above will create a new [PdoAdapter] connected to a MySQL database at
**127.0.0.1**.

## See Also

* [`PdoAdapterBuilder::setPort`](./setPort.md) - To set the port used to connect to the database
* [`PdoAdapterBuilder::setUsername`] - To set the username to use for the database
* [`PdoAdapterBuilder::setPassword`] - To set the password to use for the database
