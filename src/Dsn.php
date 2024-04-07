<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\Exception\DsnException;

use function implode;

/**
 * Utility for creating database DSN strings.
 *
 * @package Builder
 */
abstract class Dsn
{
    public const TYPE_MYSQL = 'mysql';
    public const TYPE_MARIADB = self::TYPE_MYSQL;
    public const TYPE_POSTGRES = 'pgsql';

    /**
     * Create a DSN string to connect to a `$type` database.
     *
     * @upgrade:php-8.1 Swap to match statement
     *
     * @psalm-pure
     *
     * @param non-empty-string $type                          Database type
     * @param array<non-empty-string,string|int|null> $values Connection values
     * @return non-empty-string                               Database DSN
     *
     * @throws DsnException
     *      If a DSN cannot be created for the database type
     */
    final public static function create(string $type, array $values): string
    {
        switch ($type) {
            case self::TYPE_MYSQL:
                return self::createMysql($values);
            case self::TYPE_POSTGRES:
                return self::createPostgres($values);
            default:
                throw DsnException::createUnsupported($type);
        }
    }

    /**
     * Create a DSN specific to Mysql/MariaDB.
     *
     * @psalm-pure
     *
     * @param array<non-empty-string,string|int|null> $values Connection values
     * @return non-empty-string
     */
    private static function createMysql(array $values): string
    {
        $parts = [];

        if (isset($values['socket'])) {
            $parts[] = 'unix_socket=' . $values['socket'];
        } else {
            if (isset($values['host'])) {
                $parts[] = 'host=' . $values['host'];
            }

            if (isset($values['port'])) {
                $parts[] = 'port=' . $values['port'];
            }
        }

        if (isset($values['database'])) {
            $parts[] = 'dbname=' . $values['database'];
        }

        if (isset($values['charset'])) {
            $parts[] = 'charset=' . $values['charset'];
        }

        return self::TYPE_MYSQL . ':' . implode(';', $parts);
    }

    /**
     * Create a DSN specific to Postgres.
     *
     * @psalm-pure
     *
     * @param array<non-empty-string,string|int|null> $values Connection values
     * @return non-empty-string
     */
    private static function createPostgres(array $values): string
    {
        $parts = [];

        if (isset($values['host'])) {
            $parts[] = 'host=' . $values['host'];
        } elseif (isset($values['socket'])) {
            $parts[] = 'host=' . $values['socket'];
        }

        if (isset($values['port'])) {
            $parts[] = 'port=' . $values['port'];
        }

        if (isset($values['username'])) {
            $parts[] = 'user=' . $values['username'];
        }

        if (isset($values['password'])) {
            $parts[] = 'password=' . $values['password'];
        }

        if (isset($values['database'])) {
            $parts[] = 'dbname=' . $values['database'];
        }

        return self::TYPE_POSTGRES . ':' . implode(';', $parts);
    }
}
