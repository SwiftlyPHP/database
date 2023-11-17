<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\BackendInterface;
use Swiftly\Database\Query;
use Swiftly\Database\Exception\UnauthorisedOperationException;
use Swiftly\Database\Exception\UnsupportedOperationException;
use Swiftly\Database\Collection;

/**
 * A handler object for managing interactions with a database backend.
 *
 * @package Database
 */
class Database
{
    private BackendInterface $backend;

    /**
     * Create a new database client to the provided backend.
     *
     * @param BackendInterface $backend Database backend
     */
    public function __construct(BackendInterface $backend)
    {
        $this->backend = $backend;
    }

    /**
     * Executes the given SQL query and returns the result.
     *
     * Determines if the query is valid, escapes any provided parameters and
     * then forwards the prepared query on to the current database backend.
     *
     * @param Query $query     Configured query object
     * @return Collection|null Collection containing query results
     *
     * @throws UnauthorisedOperationException
     *      If the current database user does not have the permissions needed
     * @throws UnsupportedOperationException
     *      If the current database does not support the requested operation
     */
    public function execute(Query $query): ?Collection
    {
        $sql = $query->getQuery();

        $parameters = $query->hasParameters()
            ? $this->prepareParameters($query->getParameters())
            : [];

        return $this->backend->execute($sql, $parameters);
    }

    /**
     * Escape parameters to prepare them for use in an SQL query.
     *
     * @param array<non-empty-string,Parameter> $parameters Parameters to escape
     * @return array<non-empty-string,string>               Escaped parameters
     */
    private function prepareParameters(array $parameters): array
    {
        $prepared = [];

        foreach ($parameters as $parameter) {
            $prepared[$parameter->name] = $this->backend->escape($parameter);
        }

        return $prepared;
    }

    /**
     * Prepare a new query to be executed against this database.
     *
     * Does not actually execute the query - instead it creates a new statement
     * which can be further configured using the methods on the returned `Query`
     * object.
     *
     * @see \Swiftly\Database\Query
     *
     * @param non-empty-string $sql Raw SQL query
     * @return Query                Configurable query object
     */
    public function query(string $sql): Query
    {
        $query = new Query($sql);
        $query->setDatabase($this);

        return $query;
    }
}
