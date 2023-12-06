<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\Exception\TransactionException;

/**
 * Interface implemented by database adapters that support transactions.
 *
 * @package Adapter
 */
interface TransactionInterface
{
    /**
     * Start a new database transaction.
     *
     * @throws TransactionException
     *      If a transaction is already in progress
     */
    public function startTransaction(): void;

    /**
     * Commit the most recent database transaction.
     *
     * @throws TransactionException
     *      If there is no open transaction to commit
     */
    public function commitTransaction(): void;

    /**
     * Abort the current database transaction.
     *
     * @throws TransactionException
     *      If there is no open transaction to abort
     */
    public function abortTransaction(): void;
}
