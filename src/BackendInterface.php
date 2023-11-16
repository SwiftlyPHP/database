<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\Exception\UnauthorisedOperationException;
use Swiftly\Database\Exception\UnsupportedOperationException;
use Swiftly\Database\Exception\QueryException;
use Swiftly\Database\Collection;
use Swiftly\Database\Parameter;

/**
 * Interface allowing communication with a specific database backend.
 *
 * @package Adapter
 */
interface BackendInterface
{
    /**
     * Execute the given SQL statement and return the result.
     *
     * @param non-empty-string $sql          SQL statement
     * @param array<non-empty-string,string> Parameter values
     * @return Collection|null               Collection containing query results
     *
     * @throws UnauthorisedOperationException
     *      If the database user lacks the permission required for the command.
     * @throws UnsupportedOperationException
     *      If the database does not support/understand the command.
     * @throws QueryException
     *      If any other error occurs while querying the database.
     */
    public function execute(string $sql, array $parameters = []): ?Collection;

    /**
     * Escape a parameter value for safe inclusion within an SQL statement.
     *
     * @param Parameter $parameter The parameter to escape
     * @return string              Escaped parameter string
     */
    public function escape(Parameter $parameter): string;
}