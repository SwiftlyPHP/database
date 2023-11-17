<?php declare(strict_types=1);

namespace Swiftly\Database;

/**
 * Common interface implemented by all database exceptions and errors.
 *
 * All exceptions within this library extend this interface. The result of this
 * is that you only have to type hint `ExceptionInterface` within try...catch
 * blocks, instead of listing out each possible exception.
 *
 * ```php
 * <?php
 *
 * try {
 *     $database->query(...);
 * } catch (ExceptionInterface $e) {
 *     // Handle errors
 * }
 * ```
 *
 * @package Exception
 */
interface ExceptionInterface
{
}
