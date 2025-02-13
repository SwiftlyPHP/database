<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\Exception\AdapterException;
use Swiftly\Database\Exception\QueryException;
use Swiftly\Database\Collection;
use Swiftly\Database\AbstractParameter;

/**
 * Interface allowing communication with a specific database backend.
 *
 * @package Adapter
 */
interface AdapterInterface
{
    /**
     * Execute the given SQL statement and return the result.
     *
     * The provided parameters **HAVE NOT** been escaped by this point, it is up
     * to implementors to handle their own sanitising and quoting.
     *
     * @param non-empty-string $sql                       SQL statement
     * @param array<string,AbstractParameter> $parameters Parameter values
     * @return Collection|null                            Query results
     *
     * @throws AdapterException
     *      If the database does not support the requested operation
     * @throws QueryException
     *      If there is a problem during execution of the SQL query
     */
    public function execute(string $sql, array $parameters = []): ?Collection;
}
