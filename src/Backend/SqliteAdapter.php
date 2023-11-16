<?php declare(strict_types=1);

namespace Swiftly\Database\Backend;

use Swiftly\Database\BackendInterface;
use Swiftly\Database\FileBasedDatabaseInterface;
use Swiftly\Database\FileBasedDatabaseTrait;

/**
 * Adapter for using SQLite as your database backend.
 *
 * @package Adapter
 */
class SqliteAdapter implements BackendInterface, FileBasedDatabaseInterface
{
    use FileBasedDatabaseTrait;
}