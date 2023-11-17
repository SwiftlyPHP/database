<?php declare(strict_types=1);

namespace Swiftly\Database\Exception;

use RuntimeException;
use Swiftly\Database\ExceptionInterface;

use function sprintf;

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
}
