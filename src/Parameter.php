<?php declare(strict_types=1);

namespace Swiftly\Database;

/**
 * Holds a parameter that is to be escaped and used within a query.
 *
 * @package Query
 *
 * @psalm-immutable
 */
class Parameter
{
    /** @var non-empty-string $name */
    public string $name;

    /** @var scalar|list<scalar> $value */
    public $value;

    /**
     * Create a new parameter with the given name and value.
     *
     * @param non-empty-string $name     Parameter name
     * @param scalar|list<scalar> $value Value to be escaped
     */
    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}