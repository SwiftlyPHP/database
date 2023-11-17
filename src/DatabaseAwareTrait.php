<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\Database;

/**
 * Trait used to quickly implement the `DatabaseAwareInterface`.
 *
 * @see \Swiftly\Database\DatabaseAwareInterface
 *
 * @package Utility
 * @internal
 */
trait DatabaseAwareTrait
{
    protected ?Database $database;

    /**
     * @psalm-mutation-free
     */
    final public function getDatabase(): ?Database
    {
        return $this->database;
    }

    /**
     * @psalm-external-mutation-free
     */
    final public function setDatabase(?Database $database): void
    {
        $this->database = $database;
    }
}