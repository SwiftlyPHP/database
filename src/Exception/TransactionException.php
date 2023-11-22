<?php declare(strict_types=1);

namespace Swiftly\Database\Exception;

use Exception;
use Swiftly\Database\ExceptionInterface;

use function sprintf;

/**
 * Indicates an error occured with a database transaction.
 *
 * @package Exception
 *
 * @psalm-external-mutation-free
 */
class TransactionException extends Exception implements ExceptionInterface
{
    /**
     * Create an exception to indicate the transaction callback errored.
     *
     * @param Exception $exception Exception to wrap
     * @return self                Transaction exception
     */
    public static function createFromException(Exception $exception): self
    {
        return new self(sprintf(
            "Transaction aborted as an error occured!\n%s",
            $exception->getMessage()
        ), 0, $exception);
    }

    /**
     * Create an exception to indicate that a transaction is already ongoing.
     *
     * @return self Transaction exception
     */
    public static function createInProgress(): self
    {
        return new self(
            'Failed starting new transaction as one is already in progress!'
        );
    }
}
