<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\Exception\DsnException;

use function implode;

/**
 * Utility for creating database DSN strings.
 *
 * @psalm-type DsnValues = array<non-empty-string,string|int|null>
 * @psalm-type DsnBuilder = pure-callable(DsnValues):non-empty-string
 *
 * @package Builder
 */
abstract class Dsn
{
    public const TYPE_MYSQL = 'mysql';
    public const TYPE_MARIADB = self::TYPE_MYSQL;
    public const TYPE_POSTGRES = 'pgsql';

    /** @var array<non-empty-string,DsnBuilder> */
    private static $registered = [];

    /**
     * Register a callback used to build DSNs for `$type` databases.
     *
     * @psalm-external-mutation-free
     *
     * @psalm-param DsnBuilder $builder
     *
     * @param non-empty-string $type Database type
     * @param callable $builder      DSN builder function
     */
    final public static function registerScheme(
        string $type,
        callable $builder
    ): void {
        self::$registered[$type] = $builder;
    }

    /**
     * Create a DSN string to connect to a `$type` database.
     *
     * @upgrade:php-8.1 Swap to match statement
     *
     * @psalm-external-mutation-free
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
                if (isset(self::$registered[$type])) {
                    return self::$registered[$type]($values);
                }
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

        if (isset($values['socket'])) {
            $parts[] = 'host=' . $values['socket'];
        } else {
            if (isset($values['host'])) {
                $parts[] = 'host=' . $values['host'];
            }

            if (isset($values['port'])) {
                $parts[] = 'port=' . $values['port'];
            }
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
