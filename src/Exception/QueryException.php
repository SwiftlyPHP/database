<?php declare(strict_types=1);

namespace Swiftly\Database\Exception;

use Exception;
use Swiftly\Database\ExceptionInterface;

/**
 * Base class shared by all query related exception.
 *
 * @package Exception
 *
 * @psalm-external-mutation-free
 */
class QueryException extends Exception implements ExceptionInterface
{
    /**
     * Create a new exception with the details from another.
     *
     * @param Exception $exception Exception to wrap
     * @return self                Query exception
     */
    public static function createFromException(Exception $exception): self
    {
        return new self(
            $exception->getMessage(),
            $exception->getCode(),
            $exception
        );
    }
}