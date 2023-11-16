<?php declare(strict_types=1);

namespace Swiftly\Database\Exception;

use Exception;
use Swiftly\Database\ExceptionInterface;

/**
 * Base class shared by all query related exception.
 *
 * @package Exception
 *
 * @psalm-immutable
 */
abstract class QueryException extends Exception implements ExceptionInterface
{}