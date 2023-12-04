---
since: 1.0
type: class
tags: [builder, utility, mysql, postgres]
---
# PdoAdapterBuilder

Provides a fluent interface that can be used to configure and create new
[PDO adapter]() instances.

## Class Synopsis

```php
namespace Swiftly\Database\Builder;

class PdoAdapterBuilder implements BuilderInterface
{
    public static function Mysql(): self;
    public static function Postgres(): self;
    public static function setFactory(?callable $factory): void;

    public function __construct(string $type);
    public function setHostname(?string $hostname): self;
    public function setSocket(?string $socket): self;
    public function setPort(?int $port): self;
    public function setDatabase(?string $database): self;
    public function setCharset(?string $charset): self;
    public function setUsername(?string $username): self;
    public function setPassword(?string $password): self;
    public function setOption(string $option, mixed $value): self;
    public function setAttribute(int $attribute, mixed $value): self;
    public function create(): PdoAdapter;
}
```

## Methods

* [`PdoAdapterBuilder::Mysql`](./Mysql.md) - Creates a builder for MySQL-PDO adapters
* [`PdoAdapterBuilder::Postgres`](./Postgres.md) - Creates a builder for PostgreSQL-PDO adapters
* [`PdoAdapterBuilder::setFactory`](./setFactory.md) - Change the factory used to create PDO instances
* [`PdoAdapterBuilder::__construct`](./__construct.md) - Creates a new builder for a given database type
* [`PdoAdapterBuilder::setHostname`](./setHostname.md) - Sets the hostname of the database
* [`PdoAdapterBuilder::setSocket`](./setSocket.md) - Sets the UNIX socket used to connect the database
* [`PdoAdapterBuilder::setPort`](./setPort.md) - Sets the port used to connect to the database
* [`PdoAdapterBuilder::setDatabase`](./setDatabase.md) - Sets the name of the database to use
* [`PdoAdapterBuilder::setCharset`](./setCharset.md) - Sets the character set to use
* [`PdoAdapterBuilder::setUsername`](./setUsername.md) - Sets the username to use for the database
* [`PdoAdapterBuilder::setPassword`](./setPassword.md) - Sets the password to use for the database
* [`PdoAdapterBuilder::setOption`](./setOption.md) - Sets an option to pass to [`PDO::__construct`](https://www.php.net/manual/en/pdo.construct.php)
* [`PdoAdapterBuilder::setAttribute`](./setAttribute.md) - Sets an attribute to pass to [`PDO::setAttribute`](https://www.php.net/manual/en/pdo.setattribute.php)
* [`PdoAdapterBuilder::create`](./create.md) - Creates a new adapter with the current configuration

## Usage
### Creating a MySQL Adapter

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Mysql()
    ->setHostname('127.0.0.1')
    ->setPort(3306)
    ->setUsername('root')
    ->setPassword('password')
    ->create();

$database = new Database($adapter);
$database->query(...);
```

### Creating a PostgreSQL Adapter

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$adapter = PdoAdapterBuilder::Postgres()
    ->setHostname('127.0.0.1')
    ->setPort(5432)
    ->setUsername('postgres')
    ->setPassword('password')
    ->create();

$database = new Database($adapter);
$database->query(...);
```

### Setting PDO Options and Attributes

```php
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Database;

$builder = new PdoAdapterBuilder('mysql');

$adapter = $builder
    ->setHostname('127.0.0.1')
    ->setUsername('root')
    ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION,)
    ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC)
    ->setOption(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false)
    ->create();

$database = new Database($adapter);
$database->query(...);
```

## See Also

* [PdoAdapter] - More information on the PDO adapter