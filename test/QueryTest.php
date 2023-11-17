<?php declare(strict_types=1);

namespace Swiftly\Database\Test;

use PHPUnit\Framework\TestCase;
use Swiftly\Database\Query;
use Swiftly\Database\Exception\OrphanedQueryException;
use Swiftly\Database\Database;

/**
 * @covers \Swiftly\Database\Query
 * @uses \Swiftly\Database\Parameter
 */
class QueryTest extends TestCase
{
    private Query $query;

    public function setUp(): void
    {
        $this->query = new Query('SELECT * FROM test');
    }

    public function testCanGetSqlQuery(): void
    {
        self::assertSame('SELECT * FROM test', $this->query->getQuery());
    }

    public function testCanSetQueryParameter(): Query
    {
        self::assertFalse($this->query->hasParameters());
        self::assertEmpty($this->query->getParameters());

        $this->query->setParameter('foo', 'bar');

        return $this->query;
    }

    /** @depends testCanSetQueryParameter */
    public function testCanGetQueryParameters(Query $query): void
    {
        self::assertTrue($query->hasParameters());
        self::assertNotEmpty($query->getParameters());

        $parameters = $query->getParameters();

        self::assertArrayHasKey('foo', $parameters);
        self::assertSame('foo', $parameters['foo']->name);
        self::assertSame('bar', $parameters['foo']->value);
    }

    /** @covers \Swiftly\Database\Exception\OrphanedQueryException */
    public function testWillThrowIfNoDatabaseConnected(): void
    {
        self::expectException(OrphanedQueryException::class);

        $this->query->execute();
    }

    public function testWillExecuteIfDatabaseConnected(): void
    {
        $database = self::createMock(Database::class);
        $database->expects(self::once())
            ->method('execute')
            ->with($this->query);

        $this->query->setDatabase($database);
        $this->query->execute();
    }
}