<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\Database;

/**
 * Interface for classes who need access to the database instance.
 *
 * @package Utility
 */
interface DatabaseAwareInterface
{    
    /**
     * Returns the database handler to which the current object is related.
     *
     * @return Database|null Database handler instance
     */
    public function getDatabase(): ?Database;

    /**
     * Set the database handler which relates to this object.
     *
     * @param Database|null $database Database handler instance
     */
    public function setDatabase(?Database $database): void;
}