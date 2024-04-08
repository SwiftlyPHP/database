<?php declare(strict_types=1);

namespace Swiftly\Database\Test;

use PHPUnit\Framework\TestCase;
use Swiftly\Database\Dsn;
use Swiftly\Database\Exception\DsnException;

/**
 * @covers \Swiftly\Database\Dsn
 */
class DsnTest extends TestCase
{
    public function testCanBuildDsnForMysql(): void
    {
        $mysqlDsn = Dsn::create(Dsn::TYPE_MYSQL, [
            'host' => '127.0.0.1',
            'port' => 3306,
            'username' => 'root', // Unsupported by PDO-MySql
            'password' => 'password', // Unsupported by PDO-MySql
            'database' => 'sales',
            'charset' => 'utf-8'
        ]);

        self::assertSame(
            'mysql:host=127.0.0.1;port=3306;dbname=sales;charset=utf-8',
            $mysqlDsn
        );
    }

    public function testCanBuildDsnForMysqlWithSocket(): void
    {
        $mysqlDsn = Dsn::create(Dsn::TYPE_MYSQL, [
            'socket' => '/tmp',
            'port' => 3306, // Should NOT appear in output
            'database' => 'sales'
        ]);

        self::assertSame(
            'mysql:unix_socket=/tmp;dbname=sales',
            $mysqlDsn
        );
    }

    public function testCanBuildDsnForPostgres(): void
    {
        $pgsqlDsn = Dsn::create(Dsn::TYPE_POSTGRES, [
            'host' => '127.0.0.1',
            'port' => 5432,
            'username' => 'postgres',
            'password' => 'password',
            'database' => 'sales',
            'charset' => 'utf-8' // Unsupported by PDO-PgSql
        ]);

        self::assertSame(
            'pgsql:host=127.0.0.1;port=5432;user=postgres;password=password;dbname=sales',
            $pgsqlDsn
        );
    }

    public function testCanBuildDsnForPostgresWithSocket(): void
    {
        $pgsqlDsn = Dsn::create(Dsn::TYPE_POSTGRES, [
            'socket' => '/tmp',
            'port' => 5432, // Should NOT appear in output
            'database' => 'sales',
        ]);

        self::assertSame(
            'pgsql:host=/tmp;dbname=sales',
            $pgsqlDsn
        );
    }

    public function testCanRegisterCustomDsnType(): void
    {
        Dsn::registerScheme('test', function (array $values) {
            return 'test-dsn';
        });

        $testDsn = Dsn::create('test', []);

        self::assertSame('test-dsn', $testDsn);
    }

    /** @covers \Swiftly\Database\Exception\DsnException */
    public function testWillThrowIfUnknownDsnType(): void
    {
        self::expectException(DsnException::class);

        Dsn::create('unknown', []);
    }
}

