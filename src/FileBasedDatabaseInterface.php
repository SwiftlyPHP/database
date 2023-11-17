<?php declare(strict_types=1);

namespace Swiftly\Database;

/**
 * Interface for database adapters that store their data on the local disk.
 *
 * Used to handle adapters like SQLite where the local disk is the backing
 * medium. Methods defined here can be used to get or set the directory into
 * which we wish to save our data.
 *
 * @package Adapter
 */
interface FileBasedDatabaseInterface
{
    /**
     * Returns the absolute path to the database file currently being used.
     *
     * @return string Absolute file path
     */
    public function getFilePath(): string;

    /**
     * Set the file path that the adapter should use to store its data.
     *
     * Depending on the adapter used changing this value after writes have
     * already been committed may result in lost data. Unless your use case
     * requires it you should try to set this value as early as possible, before
     * any operations have taken place.
     *
     * @param string $file_path Absolute file path
     */
    public function setFilePath(string $file_path): void;
}
