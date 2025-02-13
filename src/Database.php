<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\AdapterInterface;
use Swiftly\Database\Query;
use Swiftly\Database\Exception\QueryException;
use Swiftly\Database\Exception\AdapterException;
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
    public const TRANSACTION_ABORT = false;

    private AdapterInterface $adapter;
    private bool $inTransaction;

    /**
     * Create a new database client to the provided backend.
     *
     * @param AdapterInterface $adapter Database adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->inTransaction = false;
    }

    /**
     * Executes the given query and returns the result (if any).
     *
     * @param Query $query     Configured query object
     * @return Collection|null Collection containing query results
     *
     * @throws QueryException
     *      If there is a problem while executing the given query
     * @throws AdapterException
     *      If the current database does not support the requested operation
     */
    public function execute(Query $query): ?Collection
    {
        $sql = $query->getQuery();
        $parameters = $query->getParameters();

        return $this->adapter->execute($sql, $parameters);
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
     *     $database
     *         ->query(...)
     *         ->execute();
     *
     *     return true;
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
     *     return 'Hello world';
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
     * @throws AdapterException
     *      If the current adapter does not support transactions
     * @throws TransactionException
     *      If a transaction error occurs
     */
    public function withTransaction(callable $callback)
    {
        $this->startTransaction();

        try {
            if (self::TRANSACTION_ABORT === ($result = $callback($this))) {
                $this->adapter->abortTransaction();
            } else {
                $this->adapter->commitTransaction();
            }
        } catch (Exception $e) {
            $this->adapter->abortTransaction();

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
     * @psalm-assert-if-true TransactionInterface $this->adapter
     *
     * @return bool Adapter supports transactions
     */
    public function hasTransactionSupport(): bool
    {
        return ($this->adapter instanceof TransactionInterface);
    }

    /**
     * Start a new database transaction.
     *
     * @psalm-assert TransactionInterface $this->adapter
     *
     * @throws AdapterException
     *      If the current adapter does not support transactions
     * @throws TransactionException
     *      If there is already a transaction ongoing
     */
    private function startTransaction(): void
    {
        if (!$this->hasTransactionSupport()) {
            throw AdapterException::createForTransaction($this->adapter);
        }

        if (true === $this->inTransaction) {
            throw TransactionException::createInProgress();
        }

        $this->adapter->startTransaction();
        $this->inTransaction = true;
    }

    /**
     * Prepare a new query to be executed against this database.
     *
     * Does not actually execute the query - instead it creates a new statement
     * which can be further configured using the methods on the returned `Query`
     * object.
     *
     * ```php
     * <?php
     *
     * use Swiftly\Database\Database;
     *
     * $database = new Database(...);
     *
     * $result = $database
     *     ->query('SELECT * FROM users WHERE id = :id')
     *     ->setParameter('id', 42)
     *     ->execute();
     * ```
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
