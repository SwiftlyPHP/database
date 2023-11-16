<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\Query;
use Swiftly\Database\Exception\UnauthorisedOperationException;
use Swiftly\Database\Exception\UnsupportedOperationException;

/**
 * @package Database
 */
class Database
{
    /**
     * Executes the given SQL query and returns the result.
     *
     * Determines if the query is valid, escapes any provided parameters and
     * then forwards the prepared query on to the current database backend.
     *
     * @param Query $query Configured query object
     *
     * @throws UnauthorisedOperationException
     *      If the current database user does not have the permissions needed.
     * @throws UnsupportedOperationException
     *      If the current database does not support the requested operation.
     */
    public function execute(Query $query): void
    {
        // todo
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