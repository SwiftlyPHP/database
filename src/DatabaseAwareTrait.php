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

    public function getDatabase(): ?Database
    {
        return $this->database;
    }

    public function setDatabase(?Database $database): void
    {
        $this->database = $database;
    }
}