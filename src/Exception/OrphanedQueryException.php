<?php declare(strict_types=1);

namespace Swiftly\Database\Exception;

use Swiftly\Database\Exception\QueryException;

/**
 * Indicates a user tried to execute a query but no database was selected.
 *
 * @package Exception
 *
 * @psalm-immutable
 */
final class OrphanedQueryException extends QueryException
{
    /**
     * Static constructor used to create an orphaned query exception.
     *
     * @return self Orphaned query exception
     */
    public static function create(): self
    {
        return new self(
            "Failed to execute query as no database has been specified!",
        );
    }
}