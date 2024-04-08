<?php declare(strict_types=1);

namespace Swiftly\Database\Exception;

use Exception;
use Swiftly\Database\ExceptionInterface;

use function sprintf;

/**
 * Indicates a problem with a database DSN.
 *
 * @package Exception
 *
 * @psalm-external-mutation-free
 */
class DsnException extends Exception implements ExceptionInterface
{
    /**
     * Static constructor to warn of an unsupported database type.
     *
     * @param non-empty-string $type Database type
     */
    public static function createUnsupported(string $type): self
    {
        return new self(sprintf(
            "Failed to create a DSN for unknown database type '%s'!",
            $type
        ));
    }
}
