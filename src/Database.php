<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\BackendInterface;
use Swiftly\Database\Query;
use Swiftly\Database\Exception\UnauthorisedOperationException;
use Swiftly\Database\Exception\UnsupportedOperationException;
use Swiftly\Database\Collection;
use Swiftly\Database\TransactionInterface;
use Swiftly\Database\Exception\TransactionException;
use Exception;

/**
 * A handler object for managing interactions with a database backend.
 *
 * @package Database
 */
class Database
{
    private BackendInterface $backend;
    private bool $inTransaction;

    /**
     * Create a new database client to the provided backend.
     *
     * @param BackendInterface $backend Database backend
     */
    public function __construct(BackendInterface $backend)
    {
        $this->backend = $backend;
        $this->inTransaction = false;
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
     * Execute a query (or sequence of queries) inside a transaction.
     *
     * Sets up and starts a transaction, calls the user provided function and
     * then - depending on the state of the callback - commits or aborts the
     * transaction.
     *
     * The provided callback is passed a single argument: the object on which
     * `withTransaction` is being called.
     *
     * ```php
     * <?php
     *
     * $database->withTransaction(function (Database $database) {
     *      $database
     *          ->query(...)
     *          ->execute();
     *
     *      return true;
     * });
     * ```
     *
     * If the user provided callback returns false or throws an exception, the
     * transaction will be aborted and no data will be committed. Otherwise, the
     * return value of the callback is passed back as the return value of this
     * method.
     *
     * ```php
     * <?php
     *
     * $result = $database->withTransaction(function (Database $database) {
     *      return 'Hello world';
     * });
     *
     * assert($result === 'Hello world');
     * ```
     *
     * @template TVal
     * @psalm-param callable(self):TVal $callback
     * @psalm-return TVal
     *
     * @param callable $callback User provided function
     * @return mixed             Callback result
     *
     * @throws UnsupportedOperationException
     *      If the current adapter does not support transactions
     * @throws TransactionException
     *      If a transaction error occurs
     */
    public function withTransaction(callable $callback)
    {
        $this->startTransaction();

        try {
            if (false === ($result = $callback($this))) {
                $this->backend->abortTransaction();
            } else {
                $this->backend->commitTransaction();
            }
        } catch (Exception $e) {
            $this->backend->abortTransaction();

            throw TransactionException::createFromException($e);
        } finally {
            $this->inTransaction = false;
        }

        return $result;
    }

    /**
     * Determine if the current adapter supports transactions
     *
     * @psalm-mutation-free
     * @psalm-assert-if-true TransactionInterface $this->backend
     *
     * @return bool Adapter supports transactions
     */
    public function hasTransactionSupport(): bool
    {
        return ($this->backend instanceof TransactionInterface);
    }

    /**
     * Start a new database transaction.
     *
     * @psalm-assert TransactionInterface $this->backend
     *
     * @throws UnsupportedOperationException
     *      If the current adapter does not support transactions
     * @throws TransactionException
     *      If there is already a transaction ongoing
     */
    private function startTransaction(): void
    {
        if (!$this->hasTransactionSupport()) {
            throw UnsupportedOperationException::transaction($this->backend);
        }

        if (true === $this->inTransaction) {
            throw TransactionException::inProgress();
        }

        $this->backend->startTransaction();
        $this->inTransaction = true;
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
