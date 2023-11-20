<?php declare(strict_types=1);

namespace Swiftly\Database\Parameter;

use Swiftly\Database\AbstractParameter;

use function is_array;
use function array_values;

/**
 * Represents a set of values that should be used within a query.
 *
 * Sets are simple lists of values that are commonly used for `WHERE...IN`
 * operations.
 *
 * @package Query
 *
 * @psalm-immutable
 * @extends AbstractParameter<list<scalar>>
 */
class SetParameter extends AbstractParameter
{
    /**
     * {@inheritDoc}
     *
     * @param non-empty-string $name      Parameter name
     * @param scalar|array<scalar> $value Value to be escaped
     */
    public function __construct(string $name, $value)
    {
        if (false === is_array($value)) {
            $value = [$value];
        }

        parent::__construct($name, array_values($value));
    }
}
