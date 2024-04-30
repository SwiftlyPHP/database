---
since: 1.0
type: method
tags: [query, sql, parameter]
---
# Query::setParameter

Sets a parameter that will be used in place of a named query placeholder.

This method can be thought of as analogous to the [PDO::bindValue](https://www.php.net/manual/en/pdostatement.bindvalue.php)
method. It allows the substitution of named placeholders with given values,
performing SQL escaping as/when required.

## Method Signature

```php
public function setParameter(string $name, int|float|string|bool|array $value): self;
```

## Parameters

* `$name` - The name of the placeholder which should be replaced
* `$value` - The value(s) which should replace the placeholder

## Return Value

Returns the current instance to allow chained method calls.

## Usage
### Setting Scalar Parameters

If your SQL needs to reflect some external variable, parameters can be used to
safely insert a scalar value into the query.

```php
use Swiftly\Database\Query;

$query = new Query('SELECT * FROM Products WHERE id = :productId;');
$query->setParameter('productId', 42);

// SELECT * FROM Products WHERE id = 42;
$query->execute();
```

The provided value of `42` is correctly inserted into the query in place of the
`:productId` placeholder.

Because the method returns the current instance setting multiple parameters can
be achieved with chained calls.

```php
use Swiftly\Database\Query;

$query = new Query(
    'SELECT * FROM Products WHERE active = :active AND price > :price;'
);
$query
    ->setParameter('active', true)
    ->setParameter('price', 100);

// SELECT * FROM Products WHERE active = 1 AND price > 100;
$query->execute();
```

### Setting Set/List Parameters

A few statements in SQL, such as `WHERE...IN` and `INSERT`, allow comma
separated lists for their arguments. The `setParameter` method allows you to
pass in an array of scalar values to achieve this result.

```php
use Swiftly\Database\Query;

$query = new Query('SELECT * FROM Products WHERE id IN (:products);');
$query->setParameter('products', [17, 29, 54]);

// SELECT * FROM Products WHERE id IN (17, 29, 54); 
$query->execute();
```

## See Also

* [`Query::getParameters`](./getParameters.md) - To get the values of all named parameters
* [`Query::hasParameters`](./hasParameters.md) - To check if any parameter values have been set
* [`Database::query`] - To create a query associated with an existing database