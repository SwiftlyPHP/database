<?php declare(strict_types=1);

namespace Swiftly\Database;

/**
 * Trait used to quickly implement the `FileBasedDatabaseInterface`.
 *
 * @see \Swiftly\Database\FileBasedDatabaseInterface
 *
 * @package Utility
 * @internal
 */
trait FileBasedDatabaseTrait
{
    protected string $file_path;

    public function getFilePath(): string
    {
        return $this->file_path;
    }

    public function setFilePath(string $file_path): void
    {
        $this->file_path = $file_path;
    }
}
