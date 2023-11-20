<?php declare(strict_types=1);

namespace Swiftly\Database\Exception;

use Exception;
use Swiftly\Database\ExceptionInterface;

use function sprintf;
use function gettype;

/**
 * Exception used to indicate that a provided query parameter is invalid.
 *
 * @package Exception
 *
 * @psalm-external-mutation-free
 */
class ParameterException extends Exception implements ExceptionInterface
{
    /**
     * Static constructor to warn of an unsupported parameter type.
     *
     * @param non-empty-string $name Parameter name
     * @param mixed $value           Unsupported value
     */
    public static function createUnsupported(string $name, $value): self
    {
        return new self(sprintf(
            "Unsupported type %s provided for named parameter '%s'!",
            gettype($value),
            $name
        ));
    }
}
