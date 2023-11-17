<?php declare(strict_types=1);

namespace Swiftly\Database\Exception;

use RuntimeException;
use Swiftly\Database\ExceptionInterface;
use Swiftly\Database\BackendInterface;

use function sprintf;
use function get_class;

/**
 * Indicates the current backend does not support the requested operation.
 *
 * @package Exception
 *
 * @psalm-external-mutation-free
 */
final class UnsupportedOperationException extends RuntimeException implements
    ExceptionInterface
{
    /**
     * Static constructor used to create exception for the given backend.
     *
     * @param non-empty-string $operation Name of unsupported operation
     * @param non-empty-string $backend   Name of database backend
     * @return self                       Unsupported operation exception
     */
    public static function create(string $operation, string $backend): self
    {
        return new self(sprintf(
            "Requested operation '%s' is not supported by %s adapter!",
            $operation,
            $backend
        ));
    }

    /**
     * Static constructor for adapters that do not support transactions.
     *
     * @param BackendInterface $adapter Database adapter
     * @return self                     Unsupported operation exception
     */
    public static function transaction(BackendInterface $adapter): self
    {
        return new self(sprintf(
            "Cannot start transaction as adapter '%s' does not support them!",
            get_class($adapter)
        ));
    }
}
