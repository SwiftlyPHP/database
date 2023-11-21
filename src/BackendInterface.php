<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\Exception\UnauthorisedOperationException;
use Swiftly\Database\Exception\UnsupportedOperationException;
use Swiftly\Database\Exception\QueryException;
use Swiftly\Database\Collection;
use Swiftly\Database\AbstractParameter;

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
     * The provided parameters **HAVE NOT** been escaped by this point, it is up
     * to implementors to handle their own sanitation and quoting.
     *
     * @param non-empty-string $sql                       SQL statement
     * @param array<string,AbstractParameter> $parameters Parameter values
     * @return Collection|null                            Query results
     *
     * @throws UnauthorisedOperationException
     *      If the database user lacks the permission required for the command
     * @throws UnsupportedOperationException
     *      If the database does not support/understand the command
     * @throws QueryException
     *      If any other error occurs while querying the database
     */
    public function execute(string $sql, array $parameters = []): ?Collection;
}
