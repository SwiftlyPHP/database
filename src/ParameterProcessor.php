<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\AbstractParameter;
use Swiftly\Database\Exception\ParameterException;
use Swiftly\Database\Parameter\BooleanParameter;
use Swiftly\Database\Parameter\FloatParameter;
use Swiftly\Database\Parameter\IntegerParameter;
use Swiftly\Database\Parameter\SetParameter;
use Swiftly\Database\Parameter\StringParameter;

use function is_bool;
use function is_float;
use function is_int;
use function is_array;
use function is_numeric;
use function trim;
use function fmod;

/**
 * Handles processing query parameters and infering their type.
 *
 * @package Utility
 *
 * @psalm-immutable
 */
abstract class ParameterProcessor
{
    /**
     * Takes a value and infers the type of parameter it is.
     *
     * @upgrade Swap to match statement at php 8
     *
     * @psalm-mutation-free
     *
     * @param non-empty-string $name      Parameter name
     * @param scalar|array<scalar> $value Parameter value
     * @return AbstractParameter          Inferred parameter type
     *
     * @throws ParameterException
     *      If the given value cannot be used in an SQL query
     */
    final public static function infer(string $name, $value): AbstractParameter
    {
        switch (true) {
            case is_bool($value):
                return new BooleanParameter($name, $value);
            case is_float($value):
                return new FloatParameter($name, $value);
            case is_int($value):
                return new IntegerParameter($name, $value);
            case is_array($value):
                return new SetParameter($name, $value);
            case is_string($value):
                return self::fromString($name, $value);
            default:
                throw ParameterException::createUnsupported($name, $value);
        }
    }

    /**
     * Tries to infer a more specialised type for a string parameter
     *
     * @psalm-mutation-free
     *
     * @param non-empty-string $name Parameter name
     * @param string $value          Parameter value
     * @return AbstractParameter     Inferred parameter type
     */
    private static function fromString(
        string $name,
        string $value
    ): AbstractParameter {
        if (is_numeric($value) && trim($value) === $value) {
            return self::hasDecimalValue((float)$value)
                ? new FloatParameter($name, (float)$value)
                : new IntegerParameter($name, (int)$value);
        }

        return new StringParameter($name, $value);
    }

    /**
     * Determine if the given value has decimal points.
     *
     * @psalm-pure
     *
     * @param float $value Subject number
     * @return bool        Has decimal component
     */
    private static function hasDecimalValue(float $value): bool
    {
        return fmod($value, 1) !== 0.0;
    }
}
