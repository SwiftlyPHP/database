<?php declare(strict_types=1);

namespace Swiftly\Database\Exception;

use LogicException;
use Swiftly\Database\ExceptionInterface;
use Swiftly\Database\AdapterInterface;

use function sprintf;
use function get_class;

/**
 * Exception used to indicate a problem with a database adapter.
 *
 * If this exception is encountered it usually indicates you are attempting to
 * perform an operation that is not supported by this adapter.
 *
 * @package Exception
 *
 * @psalm-external-mutation-free
 */
class AdapterException extends LogicException implements ExceptionInterface
{
    /**
     * Return an exception warning that transactions are not supported.
     *
     * If you encounter this exception but need to use transactions you will
     * have to swap to using a different database adapter.
     *
     * @param AdapterInterface $adapter The adapter currently in use
     * @return self                     Adapter exception
     */
    public static function createForTransaction(AdapterInterface $adapter): self
    {
        return new self(sprintf(
            "Operation failed as adapter '%s' does not support transactions",
            get_class($adapter)
        ));
    }

    /**
     * Return an exception warning that a query is not attached to a database.
     *
     * You will encounter errors of this type if you instantiate query objects
     * directly and do not attach a database instance. To fix this you must
     * either:
     *
     * - Pass your query objects to the database execute method
     * - Attach the desired database to your queries
     *
     * **Calling the execute method**
     *
     * ```php
     * <?php
     *
     * use Swiftly\Database\Query;
     * use Swiftly\Database\Database;
     *
     * $query = new Query("SELECT * FROM table");
     *
     * $database = new Database(...);
     * $database->execute($query);
     * ```
     *
     * **Attaching the database manually**
     *
     * ```php
     * <?php
     *
     * use Swiftly\Database\Database;
     * use Swiftly\Database\Query;
     *
     * $database = new Database(...);
     *
     * $query = new Query("SELECT * FROM table");
     * $query->setDatabase($database);
     * $query->execute();
     * ```
     *
     * Note that using the {@link \Swiftly\Database\Database::query() Database::query()}
     * method  automatically performs the above attachment, allowing you to
     * avoid this step.
     *
     * @see \Swiftly\Database\Database::query()
     *
     * @return self Adapter exception
     */
    public static function createForOrphanedQuery(): self
    {
        return new self(
            'Failed to execute query as it has not been attached to a database'
        );
    }
}
