<?php declare(strict_types=1);

namespace Swiftly\Database\Exception;

use RuntimeException;
use Swiftly\Database\ExceptionInterface;

use function sprintf;

/**
 * Indicates a user does not have permission to perform the requested operation.
 *
 * @package Exception
 *
 * @psalm-external-mutation-free
 */
final class UnauthorisedOperationException extends RuntimeException implements
    ExceptionInterface
{
    /**
     * Static constructor for unauthorised table operations.
     *
     * @param non-empty-string $table Table name
     * @param non-empty-string $user  User name
     * @return self                   Unauthorised operation exception
     */
    public static function createForTable(string $table, string $user): self
    {
        return new self(sprintf(
            "User '%s' does not have required permissions for table '%s'!",
            $table,
            $user
        ));
    }
}