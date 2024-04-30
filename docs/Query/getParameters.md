---
since: 1.0
type: method
tags: [query, sql, parameter]
---
# Query::getParameters

Returns all the parameter values that have been set.

## Method Signature

```php
public function getParameters(): array;
```

## Return Value

An array of [`AbstractParameter`](../AbstractParameter/index.md) objects keyed
by parameter name.

## Usage
### Returning All Parameters

```php
use Swiftly\Database\Query;

$query = new Query(...);
$query
    ->setParameter('active', true)
    ->setParameter('price', 100)
    ->setParameter('manufacturer', [8, 21]);

// [
//     'active' => BooleanParameter{...},
//     'price' => IntegerParameter{...},
//     'manufacturer' => SetParameter{...}
// ]
$query->getParameters();
```

## See Also

* [`Query::setParameter`](./setParameter.md) - To set the value of a named parameter 
* [`Query::hasParameters`](./hasParameters.md) - To check if any parameter values have been set